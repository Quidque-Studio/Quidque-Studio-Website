<?php

namespace Api\Controllers\Admin;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Models\StudioPost;
use Api\Core\Str;
use RequiresAuth;

class StudioPostController
{
    private Database $db;
    private Auth $auth;
    private StudioPost $postModel;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->postModel = new StudioPost($db);
        $this->requireTeamMember();
    }

    public function index(): void
    {
        $posts = $this->db->query(
            'SELECT sp.*, sc.name as category_name 
             FROM studio_posts sp 
             LEFT JOIN studio_categories sc ON sc.id = sp.category_id 
             ORDER BY sp.created_at DESC'
        );

        View::render('admin/studio-posts/index', [
            'title' => 'Studio News',
            'user' => $this->auth->user(),
            'posts' => $posts,
            'styles' => ['studio-posts'],
        ], 'admin');
    }

    public function create(): void
    {
        $categories = $this->db->query('SELECT * FROM studio_categories ORDER BY name');

        View::render('admin/studio-posts/form', [
            'title' => 'New Post',
            'user' => $this->auth->user(),
            'post' => null,
            'categories' => $categories,
            'styles' => ['studio-posts'],
        ], 'admin');
    }

    public function store(): void
    {
        $slug = $this->generateSlug($_POST['title']);

        $content = null;
        if (!empty($_POST['content'])) {
            $content = $_POST['content'];
        }

        $tags = Str::parseTags($_POST['tags'] ?? '');

        $this->postModel->create([
            'title' => $_POST['title'],
            'slug' => $slug,
            'content' => $content,
            'category_id' => !empty($_POST['category_id']) ? $_POST['category_id'] : null,
            'tags' => $tags,
        ]);

        header('Location: /admin/studio-posts');
        exit;
    }

    public function edit(string $id): void
    {
        $post = $this->postModel->find((int) $id);

        if (!$post) {
            View::notFound();
        }

        $categories = $this->db->query('SELECT * FROM studio_categories ORDER BY name');

        View::render('admin/studio-posts/form', [
            'title' => 'Edit Post',
            'user' => $this->auth->user(),
            'post' => $post,
            'categories' => $categories,
            'styles' => ['studio-posts'],
        ], 'admin');
    }

    public function update(string $id): void
    {
        $post = $this->postModel->find((int) $id);

        if (!$post) {
            View::notFound();
        }

        $content = null;
        if (!empty($_POST['content'])) {
            $content = $_POST['content'];
        }

        $tags = Str::parseTags($_POST['tags'] ?? '');

        $this->postModel->update((int) $id, [
            'title' => $_POST['title'],
            'content' => $content,
            'category_id' => !empty($_POST['category_id']) ? $_POST['category_id'] : null,
            'tags' => $tags,
        ]);

        header('Location: /admin/studio-posts');
        exit;
    }

    public function delete(string $id): void
    {
        $this->postModel->delete((int) $id);
        header('Location: /admin/studio-posts');
        exit;
    }

    public function categories(): void
    {
        $categories = $this->db->query('SELECT * FROM studio_categories ORDER BY name');

        View::render('admin/studio-posts/categories', [
            'title' => 'Post Categories',
            'user' => $this->auth->user(),
            'categories' => $categories,
            'styles' => ['studio-posts'],
        ], 'admin');
    }

    public function storeCategory(): void
    {
        $name = trim($_POST['name']);
        $slug = $this->generateSlug($name);

        $this->db->execute(
            'INSERT INTO studio_categories (name, slug) VALUES (?, ?)',
            [$name, $slug]
        );

        header('Location: /admin/studio-posts/categories');
        exit;
    }

    public function deleteCategory(string $id): void
    {
        $this->db->execute('DELETE FROM studio_categories WHERE id = ?', [$id]);
        header('Location: /admin/studio-posts/categories');
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

    public function updateCategory(string $id): void
    {
        $name = trim($_POST['name']);
        
        $this->db->execute(
            'UPDATE studio_categories SET name = ? WHERE id = ?',
            [$name, $id]
        );

        View::setFlash('success', 'Category updated');
        header('Location: /admin/studio-posts/categories');
        exit;
    }
}