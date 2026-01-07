<?php

namespace Api\Models;

class User extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        return $this->findBy('email', $email);
    }

    public function getTeamMembers(): array
    {
        return $this->db->query(
            "SELECT u.*, tp.role_title, tp.short_bio
             FROM users u
             LEFT JOIN team_profiles tp ON tp.user_id = u.id
             WHERE u.role = 'team_member'"
        );
    }

    public function getProfile(int $userId): ?array
    {
        return $this->db->queryOne(
            "SELECT * FROM team_profiles WHERE user_id = ?",
            [$userId]
        );
    }

    public function updateProfile(int $userId, array $data): void
    {
        $existing = $this->getProfile($userId);

        if ($existing) {
            $set = implode(' = ?, ', array_keys($data)) . ' = ?';
            $this->db->execute(
                "UPDATE team_profiles SET {$set} WHERE user_id = ?",
                [...array_values($data), $userId]
            );
        } else {
            $data['user_id'] = $userId;
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_fill(0, count($data), '?'));
            $this->db->execute(
                "INSERT INTO team_profiles ({$columns}) VALUES ({$placeholders})",
                array_values($data)
            );
        }
    }

    public function getPermissions(int $userId): array
    {
        return $this->db->query(
            "SELECT p.slug FROM permissions p
             JOIN user_permissions up ON up.permission_id = p.id
             WHERE up.user_id = ?",
            [$userId]
        );
    }
}