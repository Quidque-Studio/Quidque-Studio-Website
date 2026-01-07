<?php
use Api\Core\Date;

$defaultPalette = [
    'bg' => '#012a31',
    'panel' => 'rgba(1, 42, 49, 0.5)',
    'accent' => '#9d7edb',
    'highlight' => '#ff00ff',
    'success' => '#39ffb6',
    'text' => '#e0e0e0',
    'textMuted' => '#8a9bb5',
    'border' => 'rgba(157, 126, 219, 0.2)',
];

$customPalette = json_decode($member['color_palette'] ?? '{}', true) ?: [];
$palette = array_merge($defaultPalette, $customPalette);
?>

<style>
.member-page {
    --member-bg: <?= htmlspecialchars($palette['bg']) ?>;
    --member-panel: <?= htmlspecialchars($palette['panel']) ?>;
    --member-accent: <?= htmlspecialchars($palette['accent']) ?>;
    --member-highlight: <?= htmlspecialchars($palette['highlight']) ?>;
    --member-success: <?= htmlspecialchars($palette['success']) ?>;
    --member-text: <?= htmlspecialchars($palette['text']) ?>;
    --member-text-muted: <?= htmlspecialchars($palette['textMuted']) ?>;
    --member-border: <?= htmlspecialchars($palette['border']) ?>;
}
</style>

<div class="member-page">
    <div class="member-header">
        <h1><?= htmlspecialchars($member['name']) ?>'s Blog</h1>
        <div class="member-nav">
            <a href="/team/<?= $member['id'] ?>">About</a>
            <a href="/team/<?= $member['id'] ?>/posts" class="active">Blog</a>
        </div>
    </div>

    <div class="member-content">
        <?php if ($canEdit): ?>
            <div class="toolbar">
                <a href="/team/<?= $member['id'] ?>/posts/new" class="btn btn-primary">New Post</a>
            </div>
        <?php endif; ?>

        <?php if (empty($posts)): ?>
            <p>No posts yet.</p>
        <?php else: ?>
            <ul class="post-list">
                <?php foreach ($posts as $post): ?>
                    <li>
                        <a href="/team/<?= $member['id'] ?>/posts/<?= $post['slug'] ?>">
                            <strong><?= htmlspecialchars($post['title']) ?></strong>
                            <span><?= Date::short($post['created_at']) ?></span>
                        </a>
                        <?php if ($canEdit): ?>
                            <div class="post-actions">
                                <a href="/team/<?= $member['id'] ?>/posts/<?= $post['id'] ?>/edit">Edit</a>
                                <form method="POST" action="/team/<?= $member['id'] ?>/posts/<?= $post['id'] ?>/delete" style="display:inline">
                                    <?= \Api\Core\View::csrfField() ?>
                                    <button type="submit" onclick="return confirm('Delete?')">Delete</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>