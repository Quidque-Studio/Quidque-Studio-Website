<?php

namespace Api\Controllers;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;

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
        View::render('home/index', [
            'title' => 'Quidque Studio',
            'user' => $this->auth->user(),
        ]);
    }
}