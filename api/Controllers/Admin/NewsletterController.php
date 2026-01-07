<?php

namespace Api\Controllers\Admin;

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

        if (!$this->auth->isTeamMember() || !$this->auth->hasPermission('manage_newsletter')) {
            View::notFound();
        }
    }

    public function index(): void
    {
        $newsletters = $this->db->query('SELECT * FROM newsletters ORDER BY created_at DESC');
        $subscriberCount = $this->db->queryOne(
            'SELECT COUNT(*) as count FROM subscribers WHERE unsubscribed_at IS NULL'
        )['count'];

        View::render('admin/newsletter/index', [
            'title' => 'Newsletter',
            'user' => $this->auth->user(),
            'newsletters' => $newsletters,
            'subscriberCount' => $subscriberCount,
            'styles' => ['newsletter'],
        ], 'admin');
    }

    public function subscribers(): void
    {
        $subscribers = $this->db->query(
            'SELECT s.*, u.name as user_name 
             FROM subscribers s 
             LEFT JOIN users u ON u.id = s.user_id 
             ORDER BY s.subscribed_at DESC'
        );

        View::render('admin/newsletter/subscribers', [
            'title' => 'Subscribers',
            'user' => $this->auth->user(),
            'subscribers' => $subscribers,
            'styles' => ['newsletter'],
        ], 'admin');
    }

    public function create(): void
    {
        $recentContent = [
            'projects' => $this->db->query(
                'SELECT title, slug, created_at FROM projects ORDER BY created_at DESC LIMIT 5'
            ),
            'devlogs' => $this->db->query(
                'SELECT d.title, d.slug, d.created_at, p.title as project_title 
                 FROM devlogs d 
                 JOIN projects p ON p.id = d.project_id 
                 ORDER BY d.created_at DESC LIMIT 5'
            ),
            'posts' => $this->db->query(
                'SELECT title, slug, created_at FROM studio_posts ORDER BY created_at DESC LIMIT 5'
            ),
        ];

        View::render('admin/newsletter/form', [
            'title' => 'New Newsletter',
            'user' => $this->auth->user(),
            'newsletter' => null,
            'recentContent' => $recentContent,
            'styles' => ['newsletter'],
        ], 'admin');
    }

    public function store(): void
    {
        $this->db->execute(
            'INSERT INTO newsletters (subject, content) VALUES (?, ?)',
            [$_POST['subject'], $_POST['content']]
        );

        header('Location: /admin/newsletter');
        exit;
    }

    public function edit(string $id): void
    {
        $newsletter = $this->db->queryOne('SELECT * FROM newsletters WHERE id = ?', [$id]);

        if (!$newsletter) {
            View::notFound();
        }

        View::render('admin/newsletter/form', [
            'title' => 'Edit Newsletter',
            'user' => $this->auth->user(),
            'newsletter' => $newsletter,
            'recentContent' => [],
            'styles' => ['newsletter'],
        ], 'admin');
    }

    public function update(string $id): void
    {
        $newsletter = $this->db->queryOne('SELECT * FROM newsletters WHERE id = ?', [$id]);

        if (!$newsletter || $newsletter['sent_at']) {
            View::notFound();
        }

        $this->db->execute(
            'UPDATE newsletters SET subject = ?, content = ? WHERE id = ?',
            [$_POST['subject'], $_POST['content'], $id]
        );

        header('Location: /admin/newsletter');
        exit;
    }

    public function send(string $id): void
    {
        $newsletter = $this->db->queryOne('SELECT * FROM newsletters WHERE id = ?', [$id]);

        if (!$newsletter || $newsletter['sent_at']) {
            header('Location: /admin/newsletter');
            exit;
        }

        $subscribers = $this->db->query(
            'SELECT email FROM subscribers WHERE unsubscribed_at IS NULL'
        );

        $config = require BASE_PATH . '/config/app.php';

        foreach ($subscribers as $sub) {
            $this->sendEmail($sub['email'], $newsletter['subject'], $newsletter['content'], $config['debug']);
        }

        $this->db->execute('UPDATE newsletters SET sent_at = NOW() WHERE id = ?', [$id]);

        header('Location: /admin/newsletter?sent=1');
        exit;
    }

    public function delete(string $id): void
    {
        $this->db->execute('DELETE FROM newsletters WHERE id = ?', [$id]);
        header('Location: /admin/newsletter');
        exit;
    }

    private function sendEmail(string $to, string $subject, string $content, bool $debug): void
    {
        $unsubLink = "http://{$_SERVER['HTTP_HOST']}/newsletter/unsubscribe?email=" . urlencode($to);
        $content .= "\n\n---\nUnsubscribe: {$unsubLink}";

        if ($debug) {
            $log = sprintf(
                "[%s] Newsletter to: %s\nSubject: %s\n\n",
                date('Y-m-d H:i:s'),
                $to,
                $subject
            );
            file_put_contents(BASE_PATH . '/storage/mail.log', $log, FILE_APPEND);
            return;
        }

        $headers = "From: noreply@quidque.no\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        mail($to, $subject, $content, $headers);
    }
}