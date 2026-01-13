<?php

namespace Api\Controllers;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Core\Seo;
use Api\Models\User;

class AboutController
{
    private Database $db;
    private Auth $auth;
    private User $userModel;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->userModel = new User($db);
    }

    public function index(): void
    {
        $teamMembers = $this->db->query(
            'SELECT u.id, u.name, u.avatar, tp.role_title, tp.short_bio, tp.color_palette
             FROM users u
             LEFT JOIN team_profiles tp ON tp.user_id = u.id
             WHERE u.role = ?
             ORDER BY u.name',
            ['team_member']
        );

        View::render('about/index', [
            'title' => 'About',
            'user' => $this->auth->user(),
            'teamMembers' => $teamMembers,
            'seo' => Seo::make('About', [
                'description' => 'Meet the team behind Quidque Studio.',
            ]),
            'styles' => ['about'],
        ], 'main');
    }
}