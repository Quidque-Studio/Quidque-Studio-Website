<?php

namespace Api\Core;

class Auth
{
    private Database $db;
    private ?array $user = null;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->loadUserFromSession();
    }

    private function loadUserFromSession(): void
    {
        $token = $_COOKIE['session_token'] ?? null;
        if (!$token) return;

        $session = $this->db->queryOne(
            'SELECT user_id FROM sessions WHERE token = ? AND expires_at > NOW()',
            [$token]
        );

        if ($session) {
            $this->user = $this->db->queryOne(
                'SELECT * FROM users WHERE id = ?',
                [$session['user_id']]
            );
        }
    }

    public function user(): ?array
    {
        return $this->user;
    }

    public function check(): bool
    {
        return $this->user !== null;
    }

    public function isTeamMember(): bool
    {
        return $this->user && $this->user['role'] === 'team_member';
    }

    public function hasPermission(string $slug): bool
    {
        if (!$this->user) return false;

        $result = $this->db->queryOne(
            'SELECT 1 FROM user_permissions up
             JOIN permissions p ON p.id = up.permission_id
             WHERE up.user_id = ? AND p.slug = ?',
            [$this->user['id'], $slug]
        );

        return $result !== null;
    }

    public function createMagicLink(string $email): string
    {
        $existing = $this->db->queryOne(
            'SELECT token FROM magic_links WHERE email = ? AND expires_at > NOW() AND used_at IS NULL',
            [$email]
        );

        if ($existing) {
            return $existing['token'];
        }

        $this->db->execute(
            'DELETE FROM magic_links WHERE email = ?',
            [$email]
        );

        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $this->db->execute(
            'INSERT INTO magic_links (email, token, expires_at) VALUES (?, ?, ?)',
            [$email, $token, $expires]
        );

        return $token;
    }

    public function verifyMagicLink(string $token): ?string
    {
        $link = $this->db->queryOne(
            'SELECT email FROM magic_links WHERE token = ? AND expires_at > NOW() AND used_at IS NULL',
            [$token]
        );

        if (!$link) return null;

        $this->db->execute(
            'UPDATE magic_links SET used_at = NOW() WHERE token = ?',
            [$token]
        );

        return $link['email'];
    }

    public function getAllPermissions(): array
    {
        if (!$this->user) return [];

        $perms = $this->db->query(
            'SELECT p.slug FROM permissions p
            JOIN user_permissions up ON up.permission_id = p.id
            WHERE up.user_id = ?',
            [$this->user['id']]
        );

        return array_column($perms, 'slug');
    }

    public function login(int $userId): void
    {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+30 days'));

        $this->db->execute(
            'INSERT INTO sessions (user_id, token, expires_at) VALUES (?, ?, ?)',
            [$userId, $token, $expires]
        );

        $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        
        setcookie('session_token', $token, [
            'expires' => strtotime('+30 days'),
            'path' => '/',
            'httponly' => true,
            'secure' => $secure,
            'samesite' => 'Lax',
        ]);
    }

    public function logout(): void
    {
        $token = $_COOKIE['session_token'] ?? null;

        if ($token) {
            $this->db->execute('DELETE FROM sessions WHERE token = ?', [$token]);
        }

        setcookie('session_token', '', ['expires' => 1, 'path' => '/']);
        $this->user = null;
    }

    public function generateCsrfToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }

    public function verifyCsrfToken(?string $token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    public function canRequestMagicLink(string $email): bool
    {
        $count = $this->db->queryOne(
            'SELECT COUNT(*) as count FROM magic_links 
            WHERE email = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)',
            [$email]
        );
        
        return ($count['count'] ?? 0) < 3;
    }
}