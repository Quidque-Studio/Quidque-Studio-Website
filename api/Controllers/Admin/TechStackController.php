<?php

namespace Api\Controllers\Admin;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Core\Str;
use Api\Core\Traits\RequiresAuth;

class TechStackController
{
    use RequiresAuth;

    private Database $db;
    private Auth $auth;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->requireTeamMember();
    }

    public function index(): void
    {
        $tiers = $this->db->query('SELECT * FROM tech_stack_tiers ORDER BY sort_order');
        $tech = $this->db->query(
            'SELECT ts.*, tst.name as tier_name 
             FROM tech_stack ts 
             JOIN tech_stack_tiers tst ON tst.id = ts.tier_id 
             ORDER BY tst.sort_order, ts.name'
        );

        View::render('admin/tech-stack/index', [
            'title' => 'Tech Stack',
            'user' => $this->auth->user(),
            'tiers' => $tiers,
            'tech' => $tech,
            'styles' => ['tech-stack'],
        ], 'admin');
    }

    public function storeTier(): void
    {
        $name = trim($_POST['name']);
        $maxOrder = $this->db->queryOne('SELECT MAX(sort_order) as max FROM tech_stack_tiers');
        $order = ($maxOrder['max'] ?? 0) + 1;

        $this->db->execute(
            'INSERT INTO tech_stack_tiers (name, sort_order) VALUES (?, ?)',
            [$name, $order]
        );

        header('Location: /admin/tech-stack');
        exit;
    }

    public function updateTierOrder(): void
    {
        header('Content-Type: application/json');

        $orders = $_POST['orders'] ?? [];
        foreach ($orders as $id => $order) {
            $this->db->execute(
                'UPDATE tech_stack_tiers SET sort_order = ? WHERE id = ?',
                [$order, $id]
            );
        }

        echo json_encode(['success' => true]);
    }

    public function deleteTier(string $id): void
    {
        $hasItems = $this->db->queryOne(
            'SELECT COUNT(*) as count FROM tech_stack WHERE tier_id = ?',
            [$id]
        );

        if ($hasItems['count'] > 0) {
            header('Location: /admin/tech-stack?error=tier_has_items');
            exit;
        }

        $this->db->execute('DELETE FROM tech_stack_tiers WHERE id = ?', [$id]);
        header('Location: /admin/tech-stack');
        exit;
    }

    public function storeTech(): void
    {
        $name = trim($_POST['name']);
        $slug = Str::slug($name);
        $tierId = $_POST['tier_id'];

        $this->db->execute(
            'INSERT INTO tech_stack (name, slug, tier_id) VALUES (?, ?, ?)',
            [$name, $slug, $tierId]
        );

        header('Location: /admin/tech-stack');
        exit;
    }

    public function deleteTech(string $id): void
    {
        $this->db->execute('DELETE FROM tech_stack WHERE id = ?', [$id]);
        header('Location: /admin/tech-stack');
        exit;
    }

    public function updateTier(string $id): void
    {
        $this->db->execute(
            'UPDATE tech_stack_tiers SET name = ?, sort_order = ? WHERE id = ?',
            [$_POST['name'], $_POST['sort_order'], $id]
        );

        View::setFlash('success', 'Tier updated');
        header('Location: /admin/tech-stack');
        exit;
    }

    public function updateTech(string $id): void
    {
        $name = trim($_POST['name']);
        $slug = Str::slug($name);

        $this->db->execute(
            'UPDATE tech_stack SET name = ?, slug = ?, tier_id = ? WHERE id = ?',
            [$name, $slug, $_POST['tier_id'], $id]
        );

        View::setFlash('success', 'Technology updated');
        header('Location: /admin/tech-stack');
        exit;
    }
}