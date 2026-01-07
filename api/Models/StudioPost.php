<?php

namespace Api\Models;

class StudioPost extends Model
{
    protected string $table = 'studio_posts';

    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    public function getRecent(int $limit = 5): array
    {
        return $this->db->query(
            "SELECT sp.*, sc.name as category_name
             FROM studio_posts sp
             LEFT JOIN studio_categories sc ON sc.id = sp.category_id
             ORDER BY sp.created_at DESC
             LIMIT ?",
            [$limit]
        );
    }

    public function getByCategory(int $categoryId): array
    {
        return $this->db->query(
            "SELECT * FROM studio_posts WHERE category_id = ? ORDER BY created_at DESC",
            [$categoryId]
        );
    }
}