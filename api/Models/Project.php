<?php

namespace Api\Models;

class Project extends Model
{
    protected string $table = 'projects';

    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    public function getFeatured(int $limit = 3): array
    {
        return $this->db->query(
            "SELECT * FROM projects WHERE is_featured = 1 ORDER BY updated_at DESC LIMIT ?",
            [$limit]
        );
    }

    public function getByStatus(string $status): array
    {
        return $this->db->query(
            "SELECT * FROM projects WHERE status = ? ORDER BY updated_at DESC",
            [$status]
        );
    }

    public function getAllWithThumbnail(): array
    {
        return $this->db->query(
            "SELECT p.*, m.path as thumbnail
             FROM projects p
             LEFT JOIN project_gallery pg ON pg.project_id = p.id AND pg.sort_order = 0
             LEFT JOIN media m ON m.id = pg.media_id
             ORDER BY p.updated_at DESC"
        );
    }

    public function getAuthors(int $projectId): array
    {
        return $this->db->query(
            "SELECT u.id, u.name, u.avatar, tp.role_title
             FROM users u
             JOIN project_authors pa ON pa.user_id = u.id
             LEFT JOIN team_profiles tp ON tp.user_id = u.id
             WHERE pa.project_id = ?",
            [$projectId]
        );
    }

    public function setAuthors(int $projectId, array $userIds): void
    {
        $this->db->execute("DELETE FROM project_authors WHERE project_id = ?", [$projectId]);

        foreach ($userIds as $userId) {
            $this->db->execute(
                "INSERT INTO project_authors (project_id, user_id) VALUES (?, ?)",
                [$projectId, $userId]
            );
        }
    }

    public function getTechStack(int $projectId): array
    {
        return $this->db->query(
            "SELECT ts.*, tst.name as tier_name, tst.sort_order as tier_order
             FROM tech_stack ts
             JOIN project_tech_stack pts ON pts.tech_stack_id = ts.id
             JOIN tech_stack_tiers tst ON tst.id = ts.tier_id
             WHERE pts.project_id = ?
             ORDER BY tst.sort_order, ts.name",
            [$projectId]
        );
    }

    public function setTechStack(int $projectId, array $techIds): void
    {
        $this->db->execute("DELETE FROM project_tech_stack WHERE project_id = ?", [$projectId]);

        foreach ($techIds as $techId) {
            $this->db->execute(
                "INSERT INTO project_tech_stack (project_id, tech_stack_id) VALUES (?, ?)",
                [$projectId, $techId]
            );
        }
    }

    public function getGallery(int $projectId): array
    {
        return $this->db->query(
            "SELECT m.* FROM media m
             JOIN project_gallery pg ON pg.media_id = m.id
             WHERE pg.project_id = ?
             ORDER BY pg.sort_order",
            [$projectId]
        );
    }

    public function setGallery(int $projectId, array $mediaIds): void
    {
        $this->db->execute("DELETE FROM project_gallery WHERE project_id = ?", [$projectId]);

        foreach ($mediaIds as $order => $mediaId) {
            $this->db->execute(
                "INSERT INTO project_gallery (project_id, media_id, sort_order) VALUES (?, ?, ?)",
                [$projectId, $mediaId, $order]
            );
        }
    }

    public function getResources(int $projectId): array
    {
        $resources = [];

        $resources['links'] = $this->db->query(
            "SELECT *, 'link' as type FROM resource_links WHERE project_id = ? ORDER BY sort_order",
            [$projectId]
        );
        $resources['steam'] = $this->db->query(
            "SELECT *, 'steam' as type FROM resource_steam WHERE project_id = ? ORDER BY sort_order",
            [$projectId]
        );
        $resources['itch'] = $this->db->query(
            "SELECT *, 'itch' as type FROM resource_itch WHERE project_id = ? ORDER BY sort_order",
            [$projectId]
        );
        $resources['youtube'] = $this->db->query(
            "SELECT *, 'youtube' as type FROM resource_youtube WHERE project_id = ? ORDER BY sort_order",
            [$projectId]
        );
        $resources['downloads'] = $this->db->query(
            "SELECT *, 'download' as type FROM resource_downloads WHERE project_id = ? ORDER BY sort_order",
            [$projectId]
        );
        $resources['embeds'] = $this->db->query(
            "SELECT *, 'embed' as type FROM resource_embeds WHERE project_id = ? ORDER BY sort_order",
            [$projectId]
        );

        return $resources;
    }
}