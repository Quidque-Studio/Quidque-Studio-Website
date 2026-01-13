<?php

namespace Api\Controllers\Admin;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Core\Str;
use Api\Core\Traits\RequiresAuth;
use Api\Models\Devlog;
use Api\Models\Project;

class DevlogController
{
    use RequiresAuth;

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
        $this->requirePermission('manage_projects');
    }

    private function getProjectOr404(string $projectId): array
    {
        $project = $this->projectModel->find((int) $projectId);
        if (!$project) {
            View::notFound();
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

        $tags = Str::parseTags($_POST['tags'] ?? '');

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
            View::notFound();
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
            View::notFound();
        }

        $content = null;
        if (!empty($_POST['content'])) {
            $content = $_POST['content'];
        }

        $tags = Str::parseTags($_POST['tags'] ?? '');

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
        $slug = Str::slug($title);
        $existing = $this->devlogModel->findBySlug($projectId, $slug);
        if ($existing) {
            $slug .= '-' . time();
        }
        return $slug;
    }
}