<?php

namespace Api\Controllers;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
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

        $total = $this->db->queryOne('SELECT COUNT(*) as count FROM projects')['count'];
        $paginator = new Paginator($total, $page, $perPage);

        $projects = $this->db->query(
            "SELECT p.*, m.path as thumbnail
             FROM projects p
             LEFT JOIN project_gallery pg ON pg.project_id = p.id AND pg.sort_order = 0
             LEFT JOIN media m ON m.id = pg.media_id
             ORDER BY p.updated_at DESC
             LIMIT {$paginator->perPage} OFFSET {$paginator->offset}"
        );

        View::render('projects/index', [
            'title' => 'Projects',
            'user' => $this->auth->user(),
            'projects' => $projects,
            'paginator' => $paginator,
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

        View::render('projects/show', [
            'title' => $project['title'],
            'user' => $this->auth->user(),
            'project' => $project,
            'gallery' => $gallery,
            'techStack' => $techStack,
            'authors' => $authors,
            'resources' => $resources,
            'devlogs' => $devlogs,
            'styles' => ['project-single'],
        ], 'main');
    }
}