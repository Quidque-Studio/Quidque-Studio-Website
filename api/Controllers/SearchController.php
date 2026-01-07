<?php

namespace Api\Controllers;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;

class SearchController
{
    private Database $db;
    private Auth $auth;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
    }

    public function index(): void
    {
        $query = trim($_GET['q'] ?? '');
        $results = [
            'projects' => [],
            'posts' => [],
            'devlogs' => [],
        ];

        if (strlen($query) >= 2) {
            $like = '%' . $query . '%';

            $results['projects'] = $this->db->query(
                "SELECT id, title, slug, description, 'project' as type
                 FROM projects
                 WHERE title LIKE ? OR description LIKE ?
                 ORDER BY updated_at DESC
                 LIMIT 10",
                [$like, $like]
            );

            $results['posts'] = $this->db->query(
                "SELECT id, title, slug, 'post' as type
                 FROM studio_posts
                 WHERE title LIKE ?
                 ORDER BY created_at DESC
                 LIMIT 10",
                [$like]
            );

            $results['devlogs'] = $this->db->query(
                "SELECT d.id, d.title, d.slug, p.slug as project_slug, p.title as project_title, 'devlog' as type
                 FROM devlogs d
                 JOIN projects p ON p.id = d.project_id
                 WHERE d.title LIKE ?
                 ORDER BY d.created_at DESC
                 LIMIT 10",
                [$like]
            );
        }

        $totalResults = count($results['projects']) + count($results['posts']) + count($results['devlogs']);

        View::render('search/index', [
            'title' => $query ? "Search: {$query}" : 'Search',
            'user' => $this->auth->user(),
            'query' => $query,
            'results' => $results,
            'totalResults' => $totalResults,
            'styles' => ['search'],
        ], 'main');
    }
}