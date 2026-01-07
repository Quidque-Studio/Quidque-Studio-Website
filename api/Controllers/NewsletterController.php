<?php

namespace Api\Controllers;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;

class NewsletterController
{
    private Database $db;
    private Auth $auth;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
    }

    public function subscribe(): void
    {
        $email = trim($_POST['email'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: /?newsletter=invalid');
            exit;
        }

        $existing = $this->db->queryOne('SELECT * FROM subscribers WHERE email = ?', [$email]);

        if ($existing) {
            if ($existing['unsubscribed_at']) {
                $this->db->execute(
                    'UPDATE subscribers SET unsubscribed_at = NULL, subscribed_at = NOW() WHERE id = ?',
                    [$existing['id']]
                );
            }
        } else {
            $userId = null;
            if ($this->auth->check()) {
                $userId = $this->auth->user()['id'];
            }

            $this->db->execute(
                'INSERT INTO subscribers (email, user_id) VALUES (?, ?)',
                [$email, $userId]
            );
        }

        header('Location: /?newsletter=subscribed');
        exit;
    }

    public function unsubscribe(): void
    {
        $email = trim($_GET['email'] ?? '');

        if ($email) {
            $this->db->execute(
                'UPDATE subscribers SET unsubscribed_at = NOW() WHERE email = ?',
                [$email]
            );
        }

        View::render('newsletter/unsubscribed', [
            'title' => 'Unsubscribed',
            'user' => $this->auth->user(),
        ], 'main');
    }
}