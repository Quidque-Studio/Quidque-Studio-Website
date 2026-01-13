<?php

namespace Api\Controllers;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Core\Seo;
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
        $projects = $this->db->query(
            "SELECT p.*, m.path as thumbnail
             FROM projects p
             LEFT JOIN project_gallery pg ON pg.project_id = p.id AND pg.sort_order = 0
             LEFT JOIN media m ON m.id = pg.media_id
             ORDER BY p.updated_at DESC"
        );

        $grouped = [
            'working' => [
                'title' => 'Working On',
                'description' => 'Currently in active development',
                'projects' => [],
            ],
            'planned' => [
                'title' => 'Planned',
                'description' => 'Coming soon',
                'projects' => [],
            ],
            'completed' => [
                'title' => 'Completed',
                'description' => 'Finished projects',
                'projects' => [],
            ],
            'archived' => [
                'title' => 'Archived',
                'description' => 'On hold or discontinued',
                'projects' => [],
            ],
        ];

        foreach ($projects as $project) {
            switch ($project['status']) {
                case 'in_progress':
                case 'live_service':
                    $grouped['working']['projects'][] = $project;
                    break;
                case 'planned':
                    $grouped['planned']['projects'][] = $project;
                    break;
                case 'completed':
                    $grouped['completed']['projects'][] = $project;
                    break;
                case 'on_hold':
                case 'abandoned':
                    $grouped['archived']['projects'][] = $project;
                    break;
                default:
                    $grouped['working']['projects'][] = $project;
                    break;
            }
        }

        View::render('projects/index', [
            'title' => 'Projects',
            'user' => $this->auth->user(),
            'grouped' => $grouped,
            'totalProjects' => count($projects),
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