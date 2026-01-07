<?php

namespace Api\Controllers;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Core\Str;
use Api\Core\Traits\RequiresAuth;
use Api\Models\User;
use Api\Models\MemberPost;

class MemberController
{
    use RequiresAuth;

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
            'SELECT u.*, tp.role_title, tp.short_bio, tp.about_content, tp.social_links, tp.color_palette
            FROM users u
            LEFT JOIN team_profiles tp ON tp.user_id = u.id
            WHERE u.id = ? AND u.role = ?',
            [$id, 'team_member']
        );

        if (!$member) {
            View::notFound();
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
        $this->requireOwner((int) $id);

        $this->userModel->updateProfile((int) $id, [
            'about_content' => $_POST['about_content'] ?? null,
        ]);

        header("Location: /team/{$id}");
        exit;
    }

    public function posts(string $id): void
    {
        $member = $this->db->queryOne(
            'SELECT u.*, tp.color_palette
            FROM users u
            LEFT JOIN team_profiles tp ON tp.user_id = u.id
            WHERE u.id = ? AND u.role = ?',
            [$id, 'team_member']
        );

        if (!$member) {
            View::notFound();
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
        $this->requireOwner((int) $id);

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
        $this->requireOwner((int) $id);

        $slug = $this->generateSlug($_POST['title'], (int) $id);
        $tags = Str::parseTags($_POST['tags'] ?? '');

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
        $this->requireOwner((int) $id);

        $member = $this->userModel->find((int) $id);
        $post = $this->postModel->find((int) $postId);

        if (!$post || $post['author_id'] !== (int) $id) {
            View::notFound();
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
        $this->requireOwner((int) $id);

        $post = $this->postModel->find((int) $postId);

        if (!$post || $post['author_id'] !== (int) $id) {
            View::notFound();
        }

        $tags = Str::parseTags($_POST['tags'] ?? '');

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
        $this->requireOwner((int) $id);

        $post = $this->postModel->find((int) $postId);

        if (!$post || $post['author_id'] !== (int) $id) {
            View::notFound();
        }

        $this->postModel->delete((int) $postId);
        header("Location: /team/{$id}/posts");
        exit;
    }

    public function showPost(string $id, string $slug): void
    {
        $member = $this->db->queryOne(
            'SELECT u.*, tp.color_palette
            FROM users u
            LEFT JOIN team_profiles tp ON tp.user_id = u.id
            WHERE u.id = ? AND u.role = ?',
            [$id, 'team_member']
        );

        if (!$member) {
            View::notFound();
        }

        $post = $this->postModel->findBySlug((int) $id, $slug);

        if (!$post) {
            View::notFound();
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
        $slug = Str::slug($title);
        $existing = $this->postModel->findBySlug($authorId, $slug);
        if ($existing) {
            $slug .= '-' . time();
        }
        return $slug;
    }
}