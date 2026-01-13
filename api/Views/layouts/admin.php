<?php
$userPermissions = $auth ?? null;
$perms = [];
if (isset($GLOBALS['auth'])) {
    $perms = $GLOBALS['auth']->getAllPermissions();
}
$currentPath = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= $title ?? 'Admin' ?> - Quidque Studio</title>
    <script>
        if (localStorage.getItem('adminSidebarCollapsed') === 'true') {
            document.documentElement.classList.add('admin-sidebar-collapsed');
        }
    </script>
    <link rel="stylesheet" href="/css/variables.css">
    <link rel="stylesheet" href="/css/admin/base.css">
    <link rel="stylesheet" href="/css/admin/components.css">
    <link rel="stylesheet" href="/css/admin/editor.css">
    <link rel="stylesheet" href="/css/admin/confirm-modal.css">
    <?php if (!empty($styles)): ?>
        <?php foreach ($styles as $style): ?>
            <link rel="stylesheet" href="/css/admin/<?= $style ?>.css">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="admin">
    <aside class="admin-sidebar" id="admin-sidebar">
        <div class="admin-sidebar-header" onclick="window.location.href = '/'">
            <div class="admin-sidebar-logo">
                <img src="/QuidqueLogo.png" alt="Quidque">
            </div>
            <span class="admin-sidebar-brand">Quidque</span>
        </div>
        <nav class="admin-nav">
            <a href="/admin" class="<?= $currentPath === '/admin' ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                <span>Dashboard</span>
            </a>
            <?php if (in_array('manage_projects', $perms)): ?>
                <a href="/admin/projects" class="<?= str_starts_with($currentPath, '/admin/projects') ? 'active' : '' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20 17a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3.9a2 2 0 0 1-1.69-.9l-.81-1.2a2 2 0 0 0-1.67-.9H8a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2Z"/><path d="M2 8v11a2 2 0 0 0 2 2h14"/></svg>
                    <span>Projects</span>
                </a>
            <?php endif; ?>
            <?php if (in_array('manage_posts', $perms)): ?>
                <a href="/admin/studio-posts" class="<?= str_starts_with($currentPath, '/admin/studio-posts') ? 'active' : '' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
                    <span>News</span>
                </a>
            <?php endif; ?>
            <?php if (in_array('manage_tech', $perms)): ?>
                <a href="/admin/tech-stack" class="nav-desktop <?= str_starts_with($currentPath, '/admin/tech-stack') ? 'active' : '' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m18 16 4-4-4-4"/><path d="m6 8-4 4 4 4"/><path d="m14.5 4-5 16"/></svg>
                    <span>Tech</span>
                </a>
            <?php endif; ?>
            <?php if (in_array('manage_messages', $perms)): ?>
                <a href="/admin/messages" class="nav-desktop <?= str_starts_with($currentPath, '/admin/messages') ? 'active' : '' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                    <span>Messages</span>
                </a>
            <?php endif; ?>
            <?php if (in_array('manage_users', $perms)): ?>
                <a href="/admin/users" class="nav-desktop <?= str_starts_with($currentPath, '/admin/users') ? 'active' : '' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span>Users</span>
                </a>
            <?php endif; ?>
            <?php if (in_array('manage_newsletter', $perms)): ?>
                <a href="/admin/newsletter" class="nav-desktop <?= str_starts_with($currentPath, '/admin/newsletter') ? 'active' : '' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <span>Newsletter</span>
                </a>
            <?php endif; ?>
            <a href="/" class="nav-mobile" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                <span>Site</span>
            </a>
        </nav>
        <div class="admin-sidebar-footer">
            <button class="admin-sidebar-toggle" onclick="toggleAdminSidebar()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m11 17-5-5 5-5"/><path d="m18 17-5-5 5-5"/></svg>
                <span>Collapse</span>
            </button>
        </div>
    </aside>
    <main class="admin-main">
        <header class="admin-header">
            <h1><?= $title ?></h1>
            <span>Logged in as <?= htmlspecialchars($user['name']) ?></span>
        </header>
        <div class="admin-content">
            <?php if (!empty($flash)): ?>
                <div class="alert alert-<?= $flash['type'] ?>"><?= htmlspecialchars($flash['message']) ?></div>
            <?php endif; ?>
            <?= $content ?>
        </div>
    </main>
    <script>
    function toggleAdminSidebar() {
        const sidebar = document.getElementById('admin-sidebar');
        const isCollapsed = sidebar.classList.toggle('collapsed');
        document.documentElement.classList.toggle('admin-sidebar-collapsed', isCollapsed);
        localStorage.setItem('adminSidebarCollapsed', isCollapsed);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        if (localStorage.getItem('adminSidebarCollapsed') === 'true') {
            document.getElementById('admin-sidebar').classList.add('collapsed');
        }
    });
    </script>
    <script src="/js/admin/confirm-modal.js"></script>
</body>
</html>