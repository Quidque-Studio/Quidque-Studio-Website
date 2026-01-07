<?php

namespace Api\Models;

use Api\Core\Database;

class Resource
{
    private Database $db;

    private array $tables = [
        'link' => 'resource_links',
        'steam' => 'resource_steam',
        'itch' => 'resource_itch',
        'youtube' => 'resource_youtube',
        'download' => 'resource_downloads',
        'embed' => 'resource_embeds',
    ];

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getTable(string $type): ?string
    {
        return $this->tables[$type] ?? null;
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
        $table = $this->getTable($type);
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
        $table = $this->getTable($type);
        if (!$table) return;

        $this->db->execute("DELETE FROM {$table} WHERE id = ?", [$id]);
    }

    public function deleteAllForProject(int $projectId): void
    {
        foreach ($this->tables as $table) {
            $this->db->execute("DELETE FROM {$table} WHERE project_id = ?", [$projectId]);
        }
    }
}