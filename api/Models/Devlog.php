<?php

namespace Api\Models;

class Devlog extends Model
{
    protected string $table = 'devlogs';

    public function findBySlug(int $projectId, string $slug): ?array
    {
        return $this->db->queryOne(
            "SELECT * FROM devlogs WHERE project_id = ? AND slug = ?",
            [$projectId, $slug]
        );
    }

    public function getByProject(int $projectId): array
    {
        return $this->db->query(
            "SELECT d.*, u.name as author_name
             FROM devlogs d
             LEFT JOIN users u ON u.id = d.author_id
             WHERE d.project_id = ?
             ORDER BY d.created_at DESC",
            [$projectId]
        );
    }

    public function getRecent(int $limit = 5): array
    {
        return $this->db->query(
            "SELECT d.*, p.title as project_title, p.slug as project_slug
             FROM devlogs d
             JOIN projects p ON p.id = d.project_id
             ORDER BY d.created_at DESC
             LIMIT ?",
            [$limit]
        );
    }
}