<?php

namespace Api\Controllers\Admin;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Core\Str;
use Api\Core\Traits\RequiresAuth;
use Api\Models\Project;
use Api\Models\User;
use Api\Models\Resource;

class ProjectController
{
    use RequiresAuth;

    private Database $db;
    private Auth $auth;
    private Project $projectModel;
    private Resource $resourceModel;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->projectModel = new Project($db);
        $this->resourceModel = new Resource($db);
        $this->requireTeamMember();
    }

    public function index(): void
    {
        $projects = $this->projectModel->all();

        View::render('admin/projects/index', [
            'title' => 'Projects',
            'user' => $this->auth->user(),
            'projects' => $projects,
            'styles' => ['projects'],
        ], 'admin');
    }

    public function create(): void
    {
        $techTiers = $this->db->query(
            'SELECT ts.*, tst.name as tier_name 
             FROM tech_stack ts 
             JOIN tech_stack_tiers tst ON tst.id = ts.tier_id 
             ORDER BY tst.sort_order, ts.name'
        );
        $teamMembers = (new User($this->db))->getTeamMembers();

        View::render('admin/projects/form', [
            'title' => 'New Project',
            'user' => $this->auth->user(),
            'project' => null,
            'techStack' => $techTiers,
            'teamMembers' => $teamMembers,
            'gallery' => [],
            'resources' => [],
            'selectedTech' => [],
            'selectedAuthors' => [],
            'styles' => ['projects'],
        ], 'admin');
    }

    public function store(): void
    {
        $slug = $this->generateSlug($_POST['title']);

        $projectId = $this->projectModel->create([
            'title' => $_POST['title'],
            'slug' => $slug,
            'description' => $_POST['description'] ?? null,
            'status' => $_POST['status'],
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        ]);

        if (!empty($_POST['tech_stack'])) {
            $this->projectModel->setTechStack($projectId, $_POST['tech_stack']);
        }

        if (!empty($_POST['authors'])) {
            $this->projectModel->setAuthors($projectId, $_POST['authors']);
        }

        if (!empty($_POST['gallery'])) {
            $this->projectModel->setGallery($projectId, $_POST['gallery']);
        }

        $this->saveResources($projectId);

        header('Location: /admin/projects');
        exit;
    }

    public function edit(string $id): void
    {
        $project = $this->projectModel->find((int) $id);

        if (!$project) {
            View::notFound();
        }

        $techTiers = $this->db->query(
            'SELECT ts.*, tst.name as tier_name 
             FROM tech_stack ts 
             JOIN tech_stack_tiers tst ON tst.id = ts.tier_id 
             ORDER BY tst.sort_order, ts.name'
        );
        $teamMembers = (new User($this->db))->getTeamMembers();
        $selectedTech = array_column($this->projectModel->getTechStack((int) $id), 'id');
        $selectedAuthors = array_column($this->projectModel->getAuthors((int) $id), 'id');
        $gallery = $this->projectModel->getGallery((int) $id);
        $resources = $this->resourceModel->getAllForProject((int) $id);

        View::render('admin/projects/form', [
            'title' => 'Edit Project',
            'user' => $this->auth->user(),
            'project' => $project,
            'techStack' => $techTiers,
            'teamMembers' => $teamMembers,
            'selectedTech' => $selectedTech,
            'selectedAuthors' => $selectedAuthors,
            'gallery' => $gallery,
            'resources' => $resources,
            'styles' => ['projects'],
        ], 'admin');
    }

    public function update(string $id): void
    {
        $project = $this->projectModel->find((int) $id);

        if (!$project) {
            View::notFound();
        }

        $this->projectModel->update((int) $id, [
            'title' => $_POST['title'],
            'description' => $_POST['description'] ?? null,
            'status' => $_POST['status'],
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        ]);

        $this->projectModel->setTechStack((int) $id, $_POST['tech_stack'] ?? []);
        $this->projectModel->setAuthors((int) $id, $_POST['authors'] ?? []);
        $this->projectModel->setGallery((int) $id, $_POST['gallery'] ?? []);

        $this->resourceModel->deleteAllForProject((int) $id);
        $this->saveResources((int) $id);

        header('Location: /admin/projects');
        exit;
    }

    public function delete(string $id): void
    {
        $this->resourceModel->deleteAllForProject((int) $id);
        $this->projectModel->delete((int) $id);
        header('Location: /admin/projects');
        exit;
    }

    private function saveResources(int $projectId): void
    {
        if (empty($_POST['resources'])) return;

        foreach ($_POST['resources'] as $index => $resource) {
            $type = $resource['type'];
            unset($resource['type']);
            $resource['project_id'] = $projectId;
            $resource['sort_order'] = $index;
            $this->resourceModel->create($type, $resource);
        }
    }

    private function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $existing = $this->projectModel->findBySlug($slug);
        if ($existing) {
            $slug .= '-' . time();
        }
        return $slug;
    }
}