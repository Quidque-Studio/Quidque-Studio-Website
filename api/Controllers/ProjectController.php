<?php

namespace Api\Controllers;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Core\Seo;
use Api\Core\Paginator;
use Api\Models\Project;
use Api\Models\Devlog;
use Api\Models\Resource;

class ProjectController
{
    private Database $db;
    private Auth $auth;
    private Project $projectModel;
    private Devlog $devlogModel;
    private Resource $resourceModel;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->projectModel = new Project($db);
        $this->devlogModel = new Devlog($db);
        $this->resourceModel = new Resource($db);
    }

    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $perPage = 12;
        $status = $_GET['status'] ?? null;
        $sort = $_GET['sort'] ?? 'updated';

        $validStatuses = ['planned', 'in_progress', 'completed', 'on_hold', 'abandoned', 'live_service'];
        $validSorts = ['updated', 'title', 'status'];

        if ($status && !in_array($status, $validStatuses)) {
            $status = null;
        }
        if (!in_array($sort, $validSorts)) {
            $sort = 'updated';
        }

        $whereClause = '';
        $params = [];
        if ($status) {
            $whereClause = 'WHERE p.status = ?';
            $params[] = $status;
        }

        $orderClause = match ($sort) {
            'title' => 'ORDER BY p.title ASC',
            'status' => "ORDER BY FIELD(p.status, 'live_service', 'in_progress', 'planned', 'on_hold', 'completed', 'abandoned'), p.updated_at DESC",
            default => 'ORDER BY p.updated_at DESC',
        };

        $countQuery = "SELECT COUNT(*) as count FROM projects p $whereClause";
        $total = $this->db->queryOne($countQuery, $params)['count'];
        $paginator = new Paginator($total, $page, $perPage);

        $query = "SELECT p.*, m.path as thumbnail
            FROM projects p
            LEFT JOIN project_gallery pg ON pg.project_id = p.id AND pg.sort_order = 0
            LEFT JOIN media m ON m.id = pg.media_id
            $whereClause
            $orderClause
            LIMIT {$paginator->perPage} OFFSET {$paginator->offset}";

        $projects = $this->db->query($query, $params);

        $statusCounts = $this->db->query(
            "SELECT status, COUNT(*) as count FROM projects GROUP BY status"
        );
        $counts = ['all' => 0];
        foreach ($statusCounts as $row) {
            $counts[$row['status']] = (int) $row['count'];
            $counts['all'] += (int) $row['count'];
        }

        View::render('projects/index', [
            'title' => 'Projects',
            'user' => $this->auth->user(),
            'projects' => $projects,
            'paginator' => $paginator,
            'currentStatus' => $status,
            'currentSort' => $sort,
            'statusCounts' => $counts,
            'seo' => Seo::make('Projects', [
                'description' => 'Browse all projects built by Quidque Studio. Tools, software, games and digital experiments.',
            ]),
            'styles' => ['projects'],
        ], 'main');
    }

    public function show(string $slug): void
    {
        $project = $this->projectModel->findBySlug($slug);

        if (!$project) {
            View::notFound();
        }

        $gallery = $this->projectModel->getGallery($project['id']);
        $techStack = $this->projectModel->getTechStack($project['id']);
        $authors = $this->projectModel->getAuthors($project['id']);
        $resources = $this->resourceModel->getAllForProject($project['id']);
        $devlogs = $this->devlogModel->getByProject($project['id']);

        $thumbnail = !empty($gallery) ? $gallery[0]['path'] : null;
        $description = $project['excerpt'] ?? $project['description'] ?? null;

        View::render('projects/show', [
            'title' => $project['title'],
            'user' => $this->auth->user(),
            'project' => $project,
            'gallery' => $gallery,
            'techStack' => $techStack,
            'authors' => $authors,
            'resources' => $resources,
            'devlogs' => $devlogs,
            'seo' => Seo::make($project['title'], [
                'description' => $description,
                'image' => $thumbnail,
                'imageAlt' => $project['title'],
                'type' => 'article',
            ]),
            'styles' => ['project-single'],
        ], 'main');
    }
}