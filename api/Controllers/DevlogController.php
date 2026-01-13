<?php

namespace Api\Controllers;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Core\Seo;
use Api\Models\Project;
use Api\Models\Devlog;

class DevlogController
{
    private Database $db;
    private Auth $auth;
    private Project $projectModel;
    private Devlog $devlogModel;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->projectModel = new Project($db);
        $this->devlogModel = new Devlog($db);
    }

    public function show(string $projectSlug, string $devlogSlug): void
    {
        $project = $this->projectModel->findBySlug($projectSlug);

        if (!$project) {
            View::notFound();
        }

        $devlog = $this->devlogModel->findBySlug($project['id'], $devlogSlug);

        if (!$devlog) {
            View::notFound();
        }

        $devlog['author_name'] = null;
        if ($devlog['author_id']) {
            $author = $this->db->queryOne('SELECT name FROM users WHERE id = ?', [$devlog['author_id']]);
            $devlog['author_name'] = $author['name'] ?? null;
        }

        View::render('devlogs/show', [
            'title' => $devlog['title'],
            'user' => $this->auth->user(),
            'project' => $project,
            'devlog' => $devlog,
            'seo' => Seo::noIndex($devlog['title']),
            'styles' => ['devlog-single'],
        ], 'main');
    }
}