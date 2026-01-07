<?php

namespace Api\Controllers;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Models\User;
use Api\Models\Media;

class SettingsController
{
    private Database $db;
    private Auth $auth;
    private User $userModel;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->userModel = new User($db);
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
        $user = $this->auth->user();
        $profile = null;

        if ($user['role'] === 'team_member') {
            $profile = $this->userModel->getProfile($user['id']);
        }

        View::render('settings/index', [
            'title' => 'Settings',
            'user' => $user,
            'profile' => $profile,
            'styles' => ['settings'],
        ], 'main');
    }

    public function update(): void
    {
        $user = $this->auth->user();

        $this->userModel->update($user['id'], [
            'name' => $_POST['name'],
        ]);

        header('Location: /settings?saved=1');
        exit;
    }

    public function updateAvatar(): void
    {
        $user = $this->auth->user();

        if (empty($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            header('Location: /settings?error=avatar');
            exit;
        }

        $file = $_FILES['avatar'];
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($file['type'], $allowed)) {
            header('Location: /settings?error=avatar_type');
            exit;
        }

        $avatar = $this->processAvatar($file['tmp_name'], $file['type']);

        if ($avatar) {
            $this->userModel->update($user['id'], ['avatar' => $avatar]);
        }

        header('Location: /settings?saved=1');
        exit;
    }

    public function updateProfile(): void
    {
        $user = $this->auth->user();

        if ($user['role'] !== 'team_member') {
            header('Location: /settings');
            exit;
        }

        $socialLinks = [];
        if (!empty($_POST['social_platform'])) {
            foreach ($_POST['social_platform'] as $i => $platform) {
                if (!empty($platform) && !empty($_POST['social_url'][$i])) {
                    $socialLinks[] = [
                        'platform' => $platform,
                        'url' => $_POST['social_url'][$i],
                    ];
                }
            }
        }

        $aboutContent = null;
        if (!empty($_POST['about_content'])) {
            $aboutContent = $_POST['about_content'];
        }

        $this->userModel->updateProfile($user['id'], [
            'role_title' => $_POST['role_title'] ?? null,
            'short_bio' => $_POST['short_bio'] ?? null,
            'accent_color' => $_POST['accent_color'] ?? null,
            'bg_color' => $_POST['bg_color'] ?? null,
            'about_content' => $aboutContent,
            'social_links' => json_encode($socialLinks),
        ]);

        header('Location: /settings?saved=1');
        exit;
    }

    private function processAvatar(string $source, string $mime): ?string
    {
        $image = match($mime) {
            'image/jpeg' => imagecreatefromjpeg($source),
            'image/png' => imagecreatefrompng($source),
            'image/webp' => imagecreatefromwebp($source),
            default => null,
        };

        if (!$image) return null;

        $width = imagesx($image);
        $height = imagesy($image);
        $size = min($width, $height);

        $x = ($width - $size) / 2;
        $y = ($height - $size) / 2;

        $cropped = imagecreatetruecolor(128, 128);
        imagecopyresampled($cropped, $image, 0, 0, (int)$x, (int)$y, 128, 128, $size, $size);

        $filename = bin2hex(random_bytes(16)) . '.jpg';
        $path = "/uploads/avatars/{$filename}";
        $fullPath = BASE_PATH . $path;

        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        imagejpeg($cropped, $fullPath, 85);

        imagedestroy($image);
        imagedestroy($cropped);

        return $path;
    }
}