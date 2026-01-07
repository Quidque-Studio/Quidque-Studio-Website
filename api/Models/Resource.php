<?php

namespace Api\Models;

class Resource
{
    private \Api\Core\Database $db;

    private array $tables = [
        'link' => 'resource_links',
        'steam' => 'resource_steam',
        'itch' => 'resource_itch',
        'youtube' => 'resource_youtube',
        'download' => 'resource_downloads',
        'embed' => 'resource_embeds',
    ];

    public function __construct(\Api\Core\Database $db)
    {
        $this->db = $db;
    }

    public function getAllForProject(int $projectId): array
    {
        $resources = [];

        foreach ($this->tables as $type => $table) {
            $rows = $this->db->query(
                "SELECT *, '{$type}' as type FROM {$table} WHERE project_id = ? ORDER BY sort_order",
                [$projectId]
            );
            $resources = array_merge($resources, $rows);
        }

        usort($resources, fn($a, $b) => $a['sort_order'] <=> $b['sort_order']);
        return $resources;
    }

    public function create(string $type, array $data): int
    {
        $table = $this->tables[$type] ?? null;
        if (!$table) return 0;

        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $this->db->execute(
            "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})",
            array_values($data)
        );

        return $this->db->lastInsertId();
    }

    public function delete(string $type, int $id): void
    {
        $table = $this->tables[$type] ?? null;
        if (!$table) return;

        $this->db->execute("DELETE FROM {$table} WHERE id = ?", [$id]);
    }

    public function deleteAllForProject(int $projectId): void
    {
        foreach ($this->tables as $table) {
            $this->db->execute("DELETE FROM {$table} WHERE project_id = ?", [$projectId]);
        }
    }

    public function updateSortOrder(string $type, int $id, int $order): void
    {
        $table = $this->tables[$type] ?? null;
        if (!$table) return;

        $this->db->execute("UPDATE {$table} SET sort_order = ? WHERE id = ?", [$order, $id]);
    }
}