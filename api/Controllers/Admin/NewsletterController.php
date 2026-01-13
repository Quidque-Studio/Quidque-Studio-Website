<?php

namespace Api\Controllers\Admin;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Core\Validator;
use Api\Core\Markdown;
use Api\Core\Traits\RequiresAuth;

class NewsletterController
{
    use RequiresAuth;

    private Database $db;
    private Auth $auth;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->requireAdmin();
    }

    public function index(): void
    {
        $newsletters = $this->db->query(
            'SELECT * FROM newsletters ORDER BY created_at DESC'
        );

        $subscriberCount = $this->db->queryOne(
            'SELECT COUNT(*) as count FROM subscribers WHERE unsubscribed_at IS NULL'
        )['count'];

        View::render('admin/newsletter/index', [
            'title' => 'Newsletters',
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
             WHERE s.unsubscribed_at IS NULL 
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
        View::render('admin/newsletter/form', [
            'title' => 'New Newsletter',
            'user' => $this->auth->user(),
            'newsletter' => null,
            'styles' => ['newsletter'],
        ], 'admin');
    }

    public function store(): void
    {
        $validator = Validator::make($_POST)
            ->required('subject', 'Subject')
            ->required('content', 'Content');

        if ($validator->fails()) {
            View::setFlash('error', $validator->firstError());
            header('Location: /admin/newsletter/new');
            exit;
        }

        $this->db->execute(
            'INSERT INTO newsletters (subject, content) VALUES (?, ?)',
            [$_POST['subject'], $_POST['content']]
        );

        View::setFlash('success', 'Newsletter saved as draft');
        header('Location: /admin/newsletter');
        exit;
    }

    public function edit(int $id): void
    {
        $newsletter = $this->db->queryOne('SELECT * FROM newsletters WHERE id = ?', [$id]);

        if (!$newsletter) {
            View::notFound();
        }

        View::render('admin/newsletter/form', [
            'title' => 'Edit Newsletter',
            'user' => $this->auth->user(),
            'newsletter' => $newsletter,
            'styles' => ['newsletter'],
        ], 'admin');
    }

    public function update(int $id): void
    {
        $newsletter = $this->db->queryOne('SELECT * FROM newsletters WHERE id = ?', [$id]);

        if (!$newsletter) {
            View::notFound();
        }

        if ($newsletter['sent_at']) {
            View::setFlash('error', 'Cannot edit a sent newsletter');
            header('Location: /admin/newsletter');
            exit;
        }

        $validator = Validator::make($_POST)
            ->required('subject', 'Subject')
            ->required('content', 'Content');

        if ($validator->fails()) {
            View::setFlash('error', $validator->firstError());
            header("Location: /admin/newsletter/{$id}/edit");
            exit;
        }

        $this->db->execute(
            'UPDATE newsletters SET subject = ?, content = ? WHERE id = ?',
            [$_POST['subject'], $_POST['content'], $id]
        );

        View::setFlash('success', 'Newsletter updated');
        header('Location: /admin/newsletter');
        exit;
    }

    public function preview(int $id): void
    {
        $newsletter = $this->db->queryOne('SELECT * FROM newsletters WHERE id = ?', [$id]);

        if (!$newsletter) {
            View::notFound();
        }

        $htmlContent = Markdown::toHtml($newsletter['content']);

        View::render('admin/newsletter/preview', [
            'title' => 'Preview: ' . $newsletter['subject'],
            'user' => $this->auth->user(),
            'newsletter' => $newsletter,
            'htmlContent' => $htmlContent,
            'styles' => ['newsletter'],
        ], 'admin');
    }

    public function send(int $id): void
    {
        $newsletter = $this->db->queryOne('SELECT * FROM newsletters WHERE id = ?', [$id]);

        if (!$newsletter) {
            View::notFound();
        }

        if ($newsletter['sent_at']) {
            View::setFlash('error', 'Newsletter already sent');
            header('Location: /admin/newsletter');
            exit;
        }

        $subscribers = $this->db->query(
            'SELECT email FROM subscribers WHERE unsubscribed_at IS NULL'
        );

        if (empty($subscribers)) {
            View::setFlash('error', 'No subscribers to send to');
            header('Location: /admin/newsletter');
            exit;
        }

        $htmlContent = $this->buildEmailHtml($newsletter);
        $plainContent = Markdown::toPlainText($newsletter['content']);
        $sent = 0;
        $failed = 0;

        foreach ($subscribers as $subscriber) {
            $unsubscribeUrl = $this->generateUnsubscribeUrl($subscriber['email']);
            $personalizedHtml = str_replace('{{unsubscribe_url}}', $unsubscribeUrl, $htmlContent);
            $personalizedPlain = $plainContent . "\n\n---\nUnsubscribe: {$unsubscribeUrl}";

            if ($this->sendEmail($subscriber['email'], $newsletter['subject'], $personalizedHtml, $personalizedPlain)) {
                $sent++;
            } else {
                $failed++;
            }
        }

        $this->db->execute(
            'UPDATE newsletters SET sent_at = NOW() WHERE id = ?',
            [$id]
        );

        View::setFlash('success', "Newsletter sent to {$sent} subscribers" . ($failed ? ", {$failed} failed" : ''));
        header('Location: /admin/newsletter');
        exit;
    }

    public function delete(int $id): void
    {
        $newsletter = $this->db->queryOne('SELECT * FROM newsletters WHERE id = ?', [$id]);

        if (!$newsletter) {
            View::notFound();
        }

        $this->db->execute('DELETE FROM newsletters WHERE id = ?', [$id]);

        View::setFlash('success', 'Newsletter deleted');
        header('Location: /admin/newsletter');
        exit;
    }

    private function buildEmailHtml(array $newsletter): string
    {
        $content = Markdown::toHtml($newsletter['content']);
        
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$newsletter['subject']}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
        }
        h1, h2, h3 {
            color: #012a31;
            margin-top: 1.5em;
            margin-bottom: 0.5em;
        }
        h1 { font-size: 24px; }
        h2 { font-size: 20px; }
        h3 { font-size: 18px; }
        p { margin: 1em 0; }
        a { color: #9d7edb; }
        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 2em 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        {$content}
        <div class="footer">
            <p>Quidque Studio</p>
            <p><a href="{{unsubscribe_url}}">Unsubscribe</a></p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private function generateUnsubscribeUrl(string $email): string
    {
        $token = hash_hmac('sha256', $email, $_ENV['APP_SECRET'] ?? 'quidque-secret');
        $encoded = urlencode($email);
        return "https://quidque.no/newsletter/unsubscribe?email={$encoded}&token={$token}";
    }

    private function sendEmail(string $to, string $subject, string $html, string $plain): bool
    {
        $boundary = md5(time());
        
        $headers = [
            'From: Quidque Studio <newsletter@quidque.no>',
            'MIME-Version: 1.0',
            "Content-Type: multipart/alternative; boundary=\"{$boundary}\"",
        ];

        $body = "--{$boundary}\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
        $body .= $plain . "\r\n\r\n";
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
        $body .= $html . "\r\n\r\n";
        $body .= "--{$boundary}--";

        return mail($to, $subject, $body, implode("\r\n", $headers));
    }
}