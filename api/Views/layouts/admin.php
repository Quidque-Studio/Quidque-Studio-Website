<?php
$userPermissions = $auth ?? null;
$perms = [];
if (isset($GLOBALS['auth'])) {
    $perms = $GLOBALS['auth']->getAllPermissions();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin' ?> - Quidque Studio</title>
    <link rel="stylesheet" href="/css/variables.css">
    <link rel="stylesheet" href="/css/admin/base.css">
    <link rel="stylesheet" href="/css/admin/components.css">
    <?php if (!empty($styles)): ?>
        <?php foreach ($styles as $style): ?>
            <link rel="stylesheet" href="/css/admin/<?= $style ?>.css">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="admin">
    <aside class="admin-sidebar">
        <div class="admin-logo">Quidque</div>
        <nav class="admin-nav">
            <a href="/admin">Dashboard</a>
            <a href="/admin/projects">Projects</a>
            <a href="/admin/studio-posts">Studio News</a>
            <a href="/admin/tech-stack">Tech Stack</a>
            <a href="/admin/messages">Messages</a>
            <?php if (in_array('manage_users', $perms)): ?>
                <a href="/admin/users">Users</a>
            <?php endif; ?>
            <?php if (in_array('manage_newsletter', $perms)): ?>
                <a href="/admin/newsletter">Newsletter</a>
            <?php endif; ?>
            <a href="/" target="_blank">View Site</a>
            <a href="/auth/logout">Logout</a>
        </nav>
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
</body>
</html>