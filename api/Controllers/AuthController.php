<?php

namespace Api\Controllers;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Models\User;

class AuthController
{
    private Database $db;
    private Auth $auth;
    private User $userModel;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->userModel = new User($db);
    }

    public function showLogin(): void
    {
        if ($this->auth->check()) {
            header('Location: /');
            exit;
        }

        View::render('auth/login', [
                'title' => 'Login',
                'styles' => ['auth']
            ]);
    }

    public function sendMagicLink(): void
    {
        $email = trim($_POST['email'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            View::render('auth/login', [
                'title' => 'Login',
                'error' => 'Invalid email address',
                'styles' => ['auth']
            ]);
            return;
        }

        if (!$this->auth->canRequestMagicLink($email)) {
            View::render('auth/login', [
                'title' => 'Login',
                'error' => 'Too many requests. Please try again later.',
                'styles' => ['auth']
            ]);
            return;
        }

        $token = $this->auth->createMagicLink($email);
        $link = "http://{$_SERVER['HTTP_HOST']}/auth/verify?token={$token}";

        $this->sendEmail($email, 'Your login link - Quidque Studio', $link);

        View::render('auth/check-email', [
            'title' => 'Check Your Email',
            'email' => $email,
            'styles' => ['auth']
        ], 'main');
    }

    public function verify(): void
    {
        $token = $_GET['token'] ?? '';
        $email = $this->auth->verifyMagicLink($token);

        if (!$email) {
            View::render('auth/login', [
                'title' => 'Login',
                'error' => 'Invalid or expired link',
                'styles' => ['auth']
            ]);
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            header("Location: /auth/register?email=" . urlencode($email));
            exit;
        }

        $this->auth->login($user['id']);
        header('Location: /');
        exit;
    }

    public function showRegister(): void
    {
        $email = $_GET['email'] ?? '';

        if (!$email) {
            header('Location: /auth/login');
            exit;
        }

        View::render('auth/register', [
            'title' => 'Complete Registration',
            'email' => $email,
            'styles' => ['auth']
        ], 'main');
    }

    public function register(): void
    {
        $email = trim($_POST['email'] ?? '');
        $name = trim($_POST['name'] ?? '');

        if (!$email || !$name) {
            View::render('auth/register', [
                'title' => 'Complete Registration',
                'email' => $email,
                'error' => 'Name is required',
                'styles' => ['auth']
            ], 'main');
            return;
        }

        $existing = $this->userModel->findByEmail($email);
        if ($existing) {
            $this->auth->login($existing['id']);
            header('Location: /');
            exit;
        }

        $userId = $this->userModel->create([
            'email' => $email,
            'name' => $name,
            'role' => 'user',
        ]);

        $this->auth->login($userId);
        header('Location: /');
        exit;
    }

    public function logout(): void
    {
        $this->auth->logout();
        header('Location: /');
        exit;
    }

    private function sendEmail(string $to, string $subject, string $link): void
    {
        $config = require BASE_PATH . '/config/app.php';

        if ($config['debug']) {
            $log = sprintf(
                "[%s] To: %s\nSubject: %s\nLink: %s\n\n",
                date('Y-m-d H:i:s'),
                $to,
                $subject,
                $link
            );
            file_put_contents(BASE_PATH . '/storage/mail.log', $log, FILE_APPEND);
            return;
        }

        $message = "Click here to log in:\n\n{$link}\n\nThis link expires in 15 minutes.";
        $headers = "From: noreply@quidque.no\r\n";
        mail($to, $subject, $message, $headers);
    }
}