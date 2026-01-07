<?php

namespace Api\Controllers;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Core\Paginator;
use Api\Models\StudioPost;

class BlogController
{
    private Database $db;
    private Auth $auth;
    private StudioPost $postModel;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->postModel = new StudioPost($db);
    }

    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $perPage = 10;
        $categorySlug = $_GET['category'] ?? null;
        $currentCategory = null;

        if ($categorySlug) {
            $currentCategory = $this->db->queryOne(
                'SELECT * FROM studio_categories WHERE slug = ?',
                [$categorySlug]
            );
        }

        if ($currentCategory) {
            $total = $this->db->queryOne(
                'SELECT COUNT(*) as count FROM studio_posts WHERE category_id = ?',
                [$currentCategory['id']]
            )['count'];
        } else {
            $total = $this->db->queryOne('SELECT COUNT(*) as count FROM studio_posts')['count'];
        }

        $paginator = new Paginator($total, $page, $perPage);

        if ($currentCategory) {
            $posts = $this->db->query(
                "SELECT sp.*, sc.name as category_name, sc.slug as category_slug
                 FROM studio_posts sp
                 LEFT JOIN studio_categories sc ON sc.id = sp.category_id
                 WHERE sp.category_id = ?
                 ORDER BY sp.created_at DESC
                 LIMIT {$paginator->perPage} OFFSET {$paginator->offset}",
                [$currentCategory['id']]
            );
        } else {
            $posts = $this->db->query(
                "SELECT sp.*, sc.name as category_name, sc.slug as category_slug
                 FROM studio_posts sp
                 LEFT JOIN studio_categories sc ON sc.id = sp.category_id
                 ORDER BY sp.created_at DESC
                 LIMIT {$paginator->perPage} OFFSET {$paginator->offset}"
            );
        }

        $categories = $this->db->query('SELECT * FROM studio_categories ORDER BY name');

        View::render('blog/index', [
            'title' => $currentCategory ? $currentCategory['name'] . ' - Blog' : 'Blog',
            'user' => $this->auth->user(),
            'posts' => $posts,
            'categories' => $categories,
            'currentCategory' => $currentCategory,
            'paginator' => $paginator,
            'styles' => ['blog'],
        ], 'main');
    }

    public function show(string $slug): void
    {
        $post = $this->postModel->findBySlug($slug);

        if (!$post) {
            View::notFound();
        }

        if ($post['category_id']) {
            $category = $this->db->queryOne('SELECT * FROM studio_categories WHERE id = ?', [$post['category_id']]);
            $post['category_name'] = $category['name'] ?? null;
            $post['category_slug'] = $category['slug'] ?? null;
        }

        View::render('blog/show', [
            'title' => $post['title'],
            'user' => $this->auth->user(),
            'post' => $post,
            'styles' => ['blog-single'],
        ], 'main');
    }
}