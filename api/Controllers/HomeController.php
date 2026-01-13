<?php

namespace Api\Controllers;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Core\Seo;
use Api\Models\Project;
use Api\Models\StudioPost;
use Api\Models\Devlog;
use Api\Models\MemberPost;

class HomeController
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
        $projectModel = new Project($this->db);
        $postModel = new StudioPost($this->db);
        $devlogModel = new Devlog($this->db);
        $memberPostModel = new MemberPost($this->db);

        $featuredProjects = $this->db->query(
            "SELECT p.*, m.path as thumbnail
             FROM projects p
             LEFT JOIN project_gallery pg ON pg.project_id = p.id AND pg.sort_order = 0
             LEFT JOIN media m ON m.id = pg.media_id
             WHERE p.is_featured = 1
             ORDER BY p.updated_at DESC
             LIMIT 3"
        );

        $recentPosts = $postModel->getRecent(5);
        $recentDevlogs = $devlogModel->getRecent(5);
        $recentMemberPosts = $memberPostModel->getRecent(5);

        View::render('home/index', [
            'title' => 'Quidque Studio',
            'user' => $this->auth->user(),
            'featuredProjects' => $featuredProjects,
            'recentPosts' => $recentPosts,
            'recentDevlogs' => $recentDevlogs,
            'recentMemberPosts' => $recentMemberPosts,
            'seo' => Seo::make('Quidque Studio', [
                'description' => 'Building tools, software and digital experiments from the ground up. No shortcuts, just focused development.',
                'image' => '/QuidqueLogo.png',
                'imageAlt' => 'Quidque Studio Logo',
            ]),
            'styles' => ['home'],
        ], 'main');
    }
}