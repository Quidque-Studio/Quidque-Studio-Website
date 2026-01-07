<?php

namespace Api\Core\Traits;

use Api\Core\View;

trait RequiresAuth
{
    protected function requireAuth(): void
    {
        if (!$this->auth->check()) {
            header('Location: /auth/login');
            exit;
        }
    }
    
    protected function requireTeamMember(): void
    {
        if (!$this->auth->isTeamMember()) {
            View::notFound();
        }
    }
    
    protected function requirePermission(string $permission): void
    {
        if (!$this->auth->hasPermission($permission)) {
            View::notFound();
        }
    }
    
    protected function requireOwner(int $resourceUserId): void
    {
        $this->requireOwner((int) $id);
    }
}