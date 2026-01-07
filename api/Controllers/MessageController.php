<?php

namespace Api\Controllers;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;

class MessageController
{
    private Database $db;
    private Auth $auth;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->requireAuth();
    }

    private function requireAuth(): void
    {
        if (!$this->auth->check()) {
            header('Location: /auth/login');
            exit;
        }
    }

    public function index(): void
    {
        $conversations = $this->db->query(
            'SELECT c.*,
                    (SELECT COUNT(*) FROM messages WHERE conversation_id = c.id) as message_count
             FROM conversations c
             WHERE c.user_id = ?
             ORDER BY c.updated_at DESC',
            [$this->auth->user()['id']]
        );

        View::render('messages/index', [
            'title' => 'My Messages',
            'user' => $this->auth->user(),
            'conversations' => $conversations,
            'styles' => ['messages'],
        ], 'main');
    }

    public function create(): void
    {
        View::render('messages/create', [
            'title' => 'New Message',
            'user' => $this->auth->user(),
            'styles' => ['messages'],
        ], 'main');
    }

    public function store(): void
    {
        $userId = $this->auth->user()['id'];

        $this->db->execute(
            'INSERT INTO conversations (user_id, subject) VALUES (?, ?)',
            [$userId, $_POST['subject']]
        );

        $conversationId = $this->db->lastInsertId();

        $this->db->execute(
            'INSERT INTO messages (conversation_id, sender_id, content) VALUES (?, ?, ?)',
            [$conversationId, $userId, $_POST['content']]
        );

        header("Location: /messages/{$conversationId}");
        exit;
    }

    public function show(string $id): void
    {
        $conversation = $this->db->queryOne(
            'SELECT * FROM conversations WHERE id = ? AND user_id = ?',
            [$id, $this->auth->user()['id']]
        );

        if (!$conversation) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }

        $messages = $this->db->query(
            'SELECT m.*, u.name as sender_name, u.role as sender_role
             FROM messages m
             JOIN users u ON u.id = m.sender_id
             WHERE m.conversation_id = ?
             ORDER BY m.created_at ASC',
            [$id]
        );

        View::render('messages/show', [
            'title' => $conversation['subject'],
            'user' => $this->auth->user(),
            'conversation' => $conversation,
            'messages' => $messages,
            'styles' => ['messages'],
        ], 'main');
    }

    public function reply(string $id): void
    {
        $conversation = $this->db->queryOne(
            'SELECT * FROM conversations WHERE id = ? AND user_id = ?',
            [$id, $this->auth->user()['id']]
        );

        if (!$conversation) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }

        $this->db->execute(
            'INSERT INTO messages (conversation_id, sender_id, content) VALUES (?, ?, ?)',
            [$id, $this->auth->user()['id'], $_POST['content']]
        );

        $this->db->execute(
            'UPDATE conversations SET updated_at = NOW() WHERE id = ?',
            [$id]
        );

        header("Location: /messages/{$id}");
        exit;
    }

    public function delete(string $id): void
    {
        $conversation = $this->db->queryOne(
            'SELECT * FROM conversations WHERE id = ? AND user_id = ?',
            [$id, $this->auth->user()['id']]
        );

        if (!$conversation) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }

        $this->db->execute('DELETE FROM messages WHERE conversation_id = ?', [$id]);
        $this->db->execute('DELETE FROM conversations WHERE id = ?', [$id]);

        View::setFlash('success', 'Conversation deleted');
        header('Location: /messages');
        exit;
    }
}