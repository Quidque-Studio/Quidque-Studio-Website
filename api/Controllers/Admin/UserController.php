<?php

namespace Api\Controllers\Admin;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Models\User;

class UserController
{
    private Database $db;
    private Auth $auth;
    private User $userModel;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->userModel = new User($db);
        
        if (!$this->auth->isTeamMember() || !$this->auth->hasPermission('manage_users')) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }
    }

    public function index(): void
    {
        $users = $this->db->query('SELECT * FROM users ORDER BY created_at DESC');

        View::render('admin/users/index', [
            'title' => 'Users',
            'user' => $this->auth->user(),
            'users' => $users,
            'styles' => ['users'],
        ], 'admin');
    }

    public function edit(string $id): void
    {
        $editUser = $this->userModel->find((int) $id);

        if (!$editUser) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }

        $permissions = $this->db->query('SELECT * FROM permissions ORDER BY label');
        $userPermissions = array_column($this->userModel->getPermissions((int) $id), 'slug');

        View::render('admin/users/edit', [
            'title' => 'Edit User',
            'user' => $this->auth->user(),
            'editUser' => $editUser,
            'permissions' => $permissions,
            'userPermissions' => $userPermissions,
            'styles' => ['users'],
        ], 'admin');
    }

    public function update(string $id): void
    {
        $editUser = $this->userModel->find((int) $id);

        if (!$editUser) {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }

        $this->userModel->update((int) $id, [
            'name' => $_POST['name'],
            'role' => $_POST['role'],
        ]);

        if ($_POST['role'] === 'team_member') {
            $profile = $this->userModel->getProfile((int) $id);
            if (!$profile) {
                $this->userModel->updateProfile((int) $id, [
                    'role_title' => null,
                    'short_bio' => null,
                ]);
            }
        }

        $this->db->execute('DELETE FROM user_permissions WHERE user_id = ?', [$id]);
        
        if (!empty($_POST['permissions'])) {
            foreach ($_POST['permissions'] as $permSlug) {
                $perm = $this->db->queryOne('SELECT id FROM permissions WHERE slug = ?', [$permSlug]);
                if ($perm) {
                    $this->db->execute(
                        'INSERT INTO user_permissions (user_id, permission_id) VALUES (?, ?)',
                        [$id, $perm['id']]
                    );
                }
            }
        }

        header('Location: /admin/users');
        exit;
    }

    public function delete(string $id): void
    {
        if ((int) $id === $this->auth->user()['id']) {
            header('Location: /admin/users?error=self_delete');
            exit;
        }

        $this->userModel->delete((int) $id);
        header('Location: /admin/users');
        exit;
    }
}