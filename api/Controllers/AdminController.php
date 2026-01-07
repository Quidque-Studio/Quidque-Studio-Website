<?php

namespace Api\Controllers;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;

class AdminController
{
    protected Database $db;
    protected Auth $auth;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->requireTeamMember();
    }

    protected function requireTeamMember(): void
    {
        if (!$this->auth->isTeamMember()) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }
    }

    protected function requirePermission(string $permission): void
    {
        if (!$this->auth->hasPermission($permission)) {
            http_response_code(403);
            View::render('admin/forbidden', ['title' => 'Forbidden'], 'admin');
            exit;
        }
    }

    public function dashboard(): void
    {
        $stats = [
            'projects' => $this->db->queryOne('SELECT COUNT(*) as count FROM projects')['count'],
            'devlogs' => $this->db->queryOne('SELECT COUNT(*) as count FROM devlogs')['count'],
            'studio_posts' => $this->db->queryOne('SELECT COUNT(*) as count FROM studio_posts')['count'],
            'users' => $this->db->queryOne('SELECT COUNT(*) as count FROM users')['count'],
        ];

        View::render('admin/dashboard', [
            'title' => 'Dashboard',
            'user' => $this->auth->user(),
            'stats' => $stats,
            'styles' => ['dashboard'],
        ], 'admin');
    }
}