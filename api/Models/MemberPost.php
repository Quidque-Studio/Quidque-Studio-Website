<?php

namespace Api\Models;

class MemberPost extends Model
{
    protected string $table = 'member_posts';

    public function findBySlug(int $authorId, string $slug): ?array
    {
        return $this->db->queryOne(
            "SELECT * FROM member_posts WHERE author_id = ? AND slug = ?",
            [$authorId, $slug]
        );
    }

    public function getByAuthor(int $authorId): array
    {
        return $this->db->query(
            "SELECT * FROM member_posts WHERE author_id = ? ORDER BY created_at DESC",
            [$authorId]
        );
    }

    public function getRecent(int $limit = 5): array
    {
        return $this->db->query(
            "SELECT mp.*, mp.author_id, mp.slug, u.name as author_name
             FROM member_posts mp
             JOIN users u ON u.id = mp.author_id
             ORDER BY mp.created_at DESC
             LIMIT ?",
            [$limit]
        );
    }
}