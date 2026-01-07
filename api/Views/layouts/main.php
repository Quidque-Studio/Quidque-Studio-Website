<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Quidque Studio' ?></title>
    <link rel="stylesheet" href="/css/variables.css">
    <link rel="stylesheet" href="/css/base.css">
    <?php if (!empty($styles)): ?>
        <?php foreach ($styles as $style): ?>
            <link rel="stylesheet" href="/css/<?= $style ?>.css">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <nav class="main-nav">
        <a href="/" class="nav-logo">Quidque</a>
        <div class="nav-links">
        <a href="/projects">Projects</a>
        <a href="/blog">Blog</a>
        <a href="/about">About</a>
        <a href="/search">Search</a>
        <?php if (!empty($user)): ?>
            <a href="/messages">Messages</a>
            <a href="/settings">Settings</a>
            <?php if ($user['role'] === 'team_member'): ?>
                <a href="/admin">Admin</a>
            <?php endif; ?>
            <a href="/auth/logout">Logout</a>
        <?php else: ?>
            <a href="/auth/login">Login</a>
        <?php endif; ?>
    </div>
    </nav>
    <main class="main-content">
        <?= $content ?>
    </main>
</body>
</html>