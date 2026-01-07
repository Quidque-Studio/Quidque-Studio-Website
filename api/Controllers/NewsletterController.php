<?php

namespace Api\Controllers;

use Api\Core\Database;
use Api\Core\View;

class NewsletterController
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function unsubscribe(): void
    {
        $email = $_GET['email'] ?? '';
        $token = $_GET['token'] ?? '';

        $expectedToken = hash_hmac('sha256', $email, $_ENV['APP_SECRET'] ?? 'quidque-secret');

        if (!hash_equals($expectedToken, $token)) {
            View::render('newsletter/unsubscribe', [
                'title' => 'Unsubscribe',
                'success' => false,
                'error' => 'Invalid unsubscribe link',
            ], 'main');
            return;
        }

        $subscriber = $this->db->queryOne(
            'SELECT * FROM subscribers WHERE email = ? AND unsubscribed_at IS NULL',
            [$email]
        );

        if ($subscriber) {
            $this->db->execute(
                'UPDATE subscribers SET unsubscribed_at = NOW() WHERE id = ?',
                [$subscriber['id']]
            );
        }

        View::render('newsletter/unsubscribe', [
            'title' => 'Unsubscribed',
            'success' => true,
            'email' => $email,
        ], 'main');
    }

    public function subscribe(): void
    {
        $email = trim($_POST['email'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            View::setFlash('error', 'Please enter a valid email address');
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
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
            $this->db->execute('INSERT INTO subscribers (email) VALUES (?)', [$email]);
        }

        View::setFlash('success', 'Thanks for subscribing!');
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
        exit;
    }
}