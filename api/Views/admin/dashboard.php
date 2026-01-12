<?php use Api\Core\Date; ?>

<div class="stats-grid">
    <a href="/admin/projects" class="stat-card stat-card-link">
        <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20 17a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3.9a2 2 0 0 1-1.69-.9l-.81-1.2a2 2 0 0 0-1.67-.9H8a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2Z"/><path d="M2 8v11a2 2 0 0 0 2 2h14"/></svg>
        </div>
        <div class="stat-content">
            <span class="stat-number"><?= $stats['projects'] ?></span>
            <span class="stat-label">Projects</span>
        </div>
    </a>
    <a href="/admin/projects" class="stat-card stat-card-link stat-card-purple">
        <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div class="stat-content">
            <span class="stat-number"><?= $stats['devlogs'] ?></span>
            <span class="stat-label">Devlogs</span>
        </div>
    </a>
    <a href="/admin/studio-posts" class="stat-card stat-card-link stat-card-accent">
        <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
        </div>
        <div class="stat-content">
            <span class="stat-number"><?= $stats['studio_posts'] ?></span>
            <span class="stat-label">Studio Posts</span>
        </div>
    </a>
    <a href="/admin/users" class="stat-card stat-card-link">
        <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="stat-content">
            <span class="stat-number"><?= $stats['users'] ?></span>
            <span class="stat-label">Users</span>
        </div>
    </a>
</div>

<div class="dashboard-sections">
    <div class="dashboard-section">
        <div class="dashboard-section-header">
            <h2>Quick Actions</h2>
        </div>
        <div class="quick-actions-grid">
            <a href="/admin/projects/create" class="quick-action">
                <div class="quick-action-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                </div>
                <span>New Project</span>
            </a>
            <a href="/admin/studio-posts/create" class="quick-action">
                <div class="quick-action-icon purple">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                </div>
                <span>New Post</span>
            </a>
            <a href="/admin/messages" class="quick-action">
                <div class="quick-action-icon accent">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                </div>
                <span>Messages</span>
            </a>
            <a href="/" class="quick-action" target="_blank">
                <div class="quick-action-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                </div>
                <span>View Site</span>
            </a>
        </div>
    </div>
</div>