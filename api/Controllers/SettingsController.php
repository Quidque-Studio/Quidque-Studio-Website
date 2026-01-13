<?php

namespace Api\Controllers;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Core\Validator;
use Api\Models\User;

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
        
        $subscription = $this->db->queryOne(
            'SELECT * FROM subscribers WHERE email = ? AND unsubscribed_at IS NULL',
            [$user['email']]
        );

        View::render('settings/index', [
            'title' => 'Settings',
            'user' => $user,
            'profile' => $profile,
            'isSubscribed' => $subscription !== null,
            'defaultPalette' => $this->getDefaultPalette(),
            'styles' => ['settings'],
        ], 'main');
    }

    public function update(): void
    {
        $validator = Validator::make($_POST)
            ->required('name', 'Display Name')
            ->min('name', 2, 'Display Name')
            ->max('name', 100, 'Display Name');

        if ($validator->fails()) {
            View::setFlash('error', $validator->firstError());
            header('Location: /settings');
            exit;
        }

        $this->userModel->update($this->auth->user()['id'], [
            'name' => $_POST['name'],
        ]);

        View::setFlash('success', 'Settings saved');
        header('Location: /settings');
        exit;
    }

    public function updateAvatar(): void
    {
        $user = $this->auth->user();

        if (empty($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            View::setFlash('error', 'No file uploaded');
            header('Location: /settings');
            exit;
        }

        $file = $_FILES['avatar'];
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($file['type'], $allowed)) {
            View::setFlash('error', 'Invalid file type. Use JPG, PNG or WebP');
            header('Location: /settings');
            exit;
        }

        $avatar = $this->processAvatar($file['tmp_name'], $file['type']);

        if ($avatar) {
            $this->userModel->update($user['id'], ['avatar' => $avatar]);
            View::setFlash('success', 'Avatar updated');
        } else {
            View::setFlash('error', 'Failed to process avatar');
        }

        header('Location: /settings');
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

        $colorPalette = null;
        if (!empty($_POST['palette'])) {
            $palette = [];
            $defaults = $this->getDefaultPalette();
            foreach ($_POST['palette'] as $key => $value) {
                if (isset($defaults[$key]) && !empty($value) && $value !== $defaults[$key]) {
                    $palette[$key] = $value;
                }
            }
            if (!empty($palette)) {
                $colorPalette = json_encode($palette);
            }
        }

        $this->userModel->updateProfile($user['id'], [
            'role_title' => $_POST['role_title'] ?? null,
            'short_bio' => $_POST['short_bio'] ?? null,
            'social_links' => json_encode($socialLinks),
            'color_palette' => $colorPalette,
        ]);

        View::setFlash('success', 'Profile updated');
        header('Location: /settings');
        exit;
    }

    private function getDefaultPalette(): array
    {
        return [
            'bg' => '#0a1214',
            'bgSurface' => '#0f1a1d',
            'panel' => '#142125',
            'panelHover' => '#1a2a2f',
            'primary' => '#00ffbb',
            'primaryDim' => 'rgba(0, 255, 187, 0.15)',
            'primaryGlow' => 'rgba(0, 255, 187, 0.3)',
            'accent' => '#ff00ff',
            'accentDim' => 'rgba(255, 0, 255, 0.15)',
            'accentGlow' => 'rgba(255, 0, 255, 0.3)',
            'purple' => '#9d7edb',
            'purpleDim' => 'rgba(157, 126, 219, 0.15)',
            'text' => '#f0f4f5',
            'textSecondary' => '#8a9da3',
            'textMuted' => '#5a6d73',
            'border' => 'rgba(0, 255, 187, 0.12)',
            'borderSubtle' => 'rgba(255, 255, 255, 0.06)',
        ];
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

    public function updateNewsletter(): void
    {
        $user = $this->auth->user();
        $email = $user['email'];
        
        $existing = $this->db->queryOne('SELECT * FROM subscribers WHERE email = ?', [$email]);
        
        if (isset($_POST['subscribe'])) {
            if ($existing) {
                if ($existing['unsubscribed_at']) {
                    $this->db->execute(
                        'UPDATE subscribers SET unsubscribed_at = NULL, subscribed_at = NOW(), user_id = ? WHERE id = ?',
                        [$user['id'], $existing['id']]
                    );
                }
            } else {
                $this->db->execute(
                    'INSERT INTO subscribers (email, user_id) VALUES (?, ?)',
                    [$email, $user['id']]
                );
            }
            View::setFlash('success', 'Subscribed to newsletter');
        } else {
            if ($existing && !$existing['unsubscribed_at']) {
                $this->db->execute(
                    'UPDATE subscribers SET unsubscribed_at = NOW() WHERE id = ?',
                    [$existing['id']]
                );
            }
            View::setFlash('success', 'Unsubscribed from newsletter');
        }
        
        header('Location: /settings');
        exit;
    }
}