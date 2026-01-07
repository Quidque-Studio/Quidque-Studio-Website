<?php

namespace Api\Controllers\Admin;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Models\Devlog;
use Api\Models\Project;

class DevlogController
{
    private Database $db;
    private Auth $auth;
    private Devlog $devlogModel;
    private Project $projectModel;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->devlogModel = new Devlog($db);
        $this->projectModel = new Project($db);
        $this->requireTeamMember();
    }

    private function requireTeamMember(): void
    {
        if (!$this->auth->isTeamMember()) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }
    }

    private function getProjectOr404(string $projectId): array
    {
        $project = $this->projectModel->find((int) $projectId);
        if (!$project) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }
        return $project;
    }

    public function index(string $projectId): void
    {
        $project = $this->getProjectOr404($projectId);
        $devlogs = $this->devlogModel->getByProject((int) $projectId);

        View::render('admin/devlogs/index', [
            'title' => "Devlogs: {$project['title']}",
            'user' => $this->auth->user(),
            'project' => $project,
            'devlogs' => $devlogs,
            'styles' => ['devlogs'],
        ], 'admin');
    }

    public function create(string $projectId): void
    {
        $project = $this->getProjectOr404($projectId);

        View::render('admin/devlogs/form', [
            'title' => "New Devlog: {$project['title']}",
            'user' => $this->auth->user(),
            'project' => $project,
            'devlog' => null,
            'styles' => ['devlogs'],
        ], 'admin');
    }

    public function store(string $projectId): void
    {
        $project = $this->getProjectOr404($projectId);
        $slug = $this->generateSlug($_POST['title'], (int) $projectId);

        $content = null;
        if (!empty($_POST['content'])) {
            $content = $_POST['content'];
        }

        $tags = null;
        if (!empty($_POST['tags'])) {
            $tags = json_encode(array_map('trim', explode(',', $_POST['tags'])));
        }

        $this->devlogModel->create([
            'project_id' => (int) $projectId,
            'author_id' => $this->auth->user()['id'],
            'title' => $_POST['title'],
            'slug' => $slug,
            'content' => $content,
            'tags' => $tags,
        ]);

        header("Location: /admin/projects/{$projectId}/devlogs");
        exit;
    }

    public function edit(string $projectId, string $id): void
    {
        $project = $this->getProjectOr404($projectId);
        $devlog = $this->devlogModel->find((int) $id);

        if (!$devlog || $devlog['project_id'] !== (int) $projectId) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }

        View::render('admin/devlogs/form', [
            'title' => "Edit Devlog: {$devlog['title']}",
            'user' => $this->auth->user(),
            'project' => $project,
            'devlog' => $devlog,
            'styles' => ['devlogs'],
        ], 'admin');
    }

    public function update(string $projectId, string $id): void
    {
        $project = $this->getProjectOr404($projectId);
        $devlog = $this->devlogModel->find((int) $id);

        if (!$devlog || $devlog['project_id'] !== (int) $projectId) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }

        $content = null;
        if (!empty($_POST['content'])) {
            $content = $_POST['content'];
        }

        $tags = null;
        if (!empty($_POST['tags'])) {
            $tags = json_encode(array_map('trim', explode(',', $_POST['tags'])));
        }

        $this->devlogModel->update((int) $id, [
            'title' => $_POST['title'],
            'content' => $content,
            'tags' => $tags,
        ]);

        header("Location: /admin/projects/{$projectId}/devlogs");
        exit;
    }

    public function delete(string $projectId, string $id): void
    {
        $this->devlogModel->delete((int) $id);
        header("Location: /admin/projects/{$projectId}/devlogs");
        exit;
    }

    private function generateSlug(string $title, int $projectId): string
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        $existing = $this->devlogModel->findBySlug($projectId, $slug);
        if ($existing) {
            $slug .= '-' . time();
        }

        return $slug;
    }
}