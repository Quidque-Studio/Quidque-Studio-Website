<?php

namespace Api\Controllers\Admin;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Core\Traits\RequiresAuth;

class MessageController
{
    use RequiresAuth;

    private Database $db;
    private Auth $auth;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->requirePermission('manage_messages');
    }

    public function index(): void
    {
        $conversations = $this->db->query(
            'SELECT c.*, u.name as user_name, u.email as user_email,
                    (SELECT COUNT(*) FROM messages WHERE conversation_id = c.id) as message_count
             FROM conversations c
             JOIN users u ON u.id = c.user_id
             ORDER BY c.updated_at DESC'
        );

        View::render('admin/messages/index', [
            'title' => 'Messages',
            'user' => $this->auth->user(),
            'conversations' => $conversations,
            'styles' => ['messages'],
        ], 'admin');
    }

    public function show(string $id): void
    {
        $conversation = $this->db->queryOne(
            'SELECT c.*, u.name as user_name, u.email as user_email
             FROM conversations c
             JOIN users u ON u.id = c.user_id
             WHERE c.id = ?',
            [$id]
        );

        if (!$conversation) {
            View::notFound();
        }

        $messages = $this->db->query(
            'SELECT m.*, u.name as sender_name, u.role as sender_role
             FROM messages m
             JOIN users u ON u.id = m.sender_id
             WHERE m.conversation_id = ?
             ORDER BY m.created_at ASC',
            [$id]
        );

        View::render('admin/messages/show', [
            'title' => $conversation['subject'],
            'user' => $this->auth->user(),
            'conversation' => $conversation,
            'messages' => $messages,
            'styles' => ['messages'],
        ], 'admin');
    }

    public function reply(string $id): void
    {
        $conversation = $this->db->queryOne(
            'SELECT * FROM conversations WHERE id = ?',
            [$id]
        );

        if (!$conversation) {
            View::notFound();
        }

        $this->db->execute(
            'INSERT INTO messages (conversation_id, sender_id, content) VALUES (?, ?, ?)',
            [$id, $this->auth->user()['id'], $_POST['content']]
        );

        $this->db->execute(
            'UPDATE conversations SET updated_at = NOW() WHERE id = ?',
            [$id]
        );

        header("Location: /admin/messages/{$id}");
        exit;
    }

    public function delete(string $id): void
    {
        $conversation = $this->db->queryOne(
            'SELECT * FROM conversations WHERE id = ?',
            [$id]
        );

        if (!$conversation) {
            View::notFound();
        }

        $this->db->execute('DELETE FROM messages WHERE conversation_id = ?', [$id]);
        $this->db->execute('DELETE FROM conversations WHERE id = ?', [$id]);

        View::setFlash('success', 'Conversation deleted');
        header('Location: /admin/messages');
        exit;
    }
}