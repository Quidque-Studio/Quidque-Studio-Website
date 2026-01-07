<?php

namespace Api\Controllers;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Models\User;
use Api\Models\MemberPost;

class MemberController
{
    private Database $db;
    private Auth $auth;
    private User $userModel;
    private MemberPost $postModel;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->userModel = new User($db);
        $this->postModel = new MemberPost($db);
    }

    public function show(string $id): void
    {
        $member = $this->db->queryOne(
            'SELECT u.*, tp.role_title, tp.short_bio, tp.about_content, tp.social_links, tp.accent_color, tp.bg_color
             FROM users u
             LEFT JOIN team_profiles tp ON tp.user_id = u.id
             WHERE u.id = ? AND u.role = ?',
            [$id, 'team_member']
        );

        if (!$member) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }

        $canEdit = $this->auth->check() && $this->auth->user()['id'] === (int) $id;

        View::render('members/show', [
            'title' => $member['name'],
            'user' => $this->auth->user(),
            'member' => $member,
            'canEdit' => $canEdit,
            'styles' => ['member'],
        ], 'main');
    }

    public function updateAbout(string $id): void
    {
        if (!$this->auth->check() || $this->auth->user()['id'] !== (int) $id) {
            http_response_code(403);
            echo '403 Forbidden';
            exit;
        }

        $this->userModel->updateProfile((int) $id, [
            'about_content' => $_POST['about_content'] ?? null,
        ]);

        header("Location: /team/{$id}");
        exit;
    }

    public function posts(string $id): void
    {
        $member = $this->db->queryOne(
            'SELECT u.*, tp.accent_color, tp.bg_color
             FROM users u
             LEFT JOIN team_profiles tp ON tp.user_id = u.id
             WHERE u.id = ? AND u.role = ?',
            [$id, 'team_member']
        );

        if (!$member) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }

        $posts = $this->postModel->getByAuthor((int) $id);
        $canEdit = $this->auth->check() && $this->auth->user()['id'] === (int) $id;

        View::render('members/posts', [
            'title' => "{$member['name']}'s Blog",
            'user' => $this->auth->user(),
            'member' => $member,
            'posts' => $posts,
            'canEdit' => $canEdit,
            'styles' => ['member'],
        ], 'main');
    }

    public function createPost(string $id): void
    {
        if (!$this->auth->check() || $this->auth->user()['id'] !== (int) $id) {
            http_response_code(403);
            echo '403 Forbidden';
            exit;
        }

        $member = $this->userModel->find((int) $id);

        View::render('members/post-form', [
            'title' => 'New Post',
            'user' => $this->auth->user(),
            'member' => $member,
            'post' => null,
            'styles' => ['member'],
        ], 'main');
    }

    public function storePost(string $id): void
    {
        if (!$this->auth->check() || $this->auth->user()['id'] !== (int) $id) {
            http_response_code(403);
            echo '403 Forbidden';
            exit;
        }

        $slug = $this->generateSlug($_POST['title'], (int) $id);

        $tags = null;
        if (!empty($_POST['tags'])) {
            $tags = json_encode(array_map('trim', explode(',', $_POST['tags'])));
        }

        $this->postModel->create([
            'author_id' => (int) $id,
            'title' => $_POST['title'],
            'slug' => $slug,
            'content' => $_POST['content'] ?? null,
            'tags' => $tags,
        ]);

        header("Location: /team/{$id}/posts");
        exit;
    }

    public function editPost(string $id, string $postId): void
    {
        if (!$this->auth->check() || $this->auth->user()['id'] !== (int) $id) {
            http_response_code(403);
            echo '403 Forbidden';
            exit;
        }

        $member = $this->userModel->find((int) $id);
        $post = $this->postModel->find((int) $postId);

        if (!$post || $post['author_id'] !== (int) $id) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }

        View::render('members/post-form', [
            'title' => 'Edit Post',
            'user' => $this->auth->user(),
            'member' => $member,
            'post' => $post,
            'styles' => ['member'],
        ], 'main');
    }

    public function updatePost(string $id, string $postId): void
    {
        if (!$this->auth->check() || $this->auth->user()['id'] !== (int) $id) {
            http_response_code(403);
            echo '403 Forbidden';
            exit;
        }

        $post = $this->postModel->find((int) $postId);

        if (!$post || $post['author_id'] !== (int) $id) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }

        $tags = null;
        if (!empty($_POST['tags'])) {
            $tags = json_encode(array_map('trim', explode(',', $_POST['tags'])));
        }

        $this->postModel->update((int) $postId, [
            'title' => $_POST['title'],
            'content' => $_POST['content'] ?? null,
            'tags' => $tags,
        ]);

        header("Location: /team/{$id}/posts");
        exit;
    }

    public function deletePost(string $id, string $postId): void
    {
        if (!$this->auth->check() || $this->auth->user()['id'] !== (int) $id) {
            http_response_code(403);
            echo '403 Forbidden';
            exit;
        }

        $post = $this->postModel->find((int) $postId);

        if (!$post || $post['author_id'] !== (int) $id) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }

        $this->postModel->delete((int) $postId);
        header("Location: /team/{$id}/posts");
        exit;
    }

    public function showPost(string $id, string $slug): void
    {
        $member = $this->db->queryOne(
            'SELECT u.*, tp.accent_color, tp.bg_color
            FROM users u
            LEFT JOIN team_profiles tp ON tp.user_id = u.id
            WHERE u.id = ? AND u.role = ?',
            [$id, 'team_member']
        );

        if (!$member) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }

        $post = $this->postModel->findBySlug((int) $id, $slug);

        if (!$post) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }

        $canEdit = $this->auth->check() && $this->auth->user()['id'] === (int) $id;

        View::render('members/post-single', [
            'title' => $post['title'],
            'user' => $this->auth->user(),
            'member' => $member,
            'post' => $post,
            'canEdit' => $canEdit,
            'styles' => ['member'],
        ], 'main');
    }

    private function generateSlug(string $title, int $authorId): string
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        $existing = $this->postModel->findBySlug($authorId, $slug);
        if ($existing) {
            $slug .= '-' . time();
        }

        return $slug;
    }
}