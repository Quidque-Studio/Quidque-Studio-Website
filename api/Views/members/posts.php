<?php
use Api\Core\Date;

$defaultPalette = [
    'bg' => '#0a1214',
    'panel' => '#142125',
    'accent' => '#9d7edb',
    'text' => '#f0f4f5',
    'textMuted' => '#5a6d73',
    'border' => 'rgba(255, 255, 255, 0.06)',
];

$customPalette = json_decode($member['color_palette'] ?? '{}', true) ?: [];
$palette = array_merge($defaultPalette, $customPalette);
?>

<style>
.member-page {
    --member-bg: <?= htmlspecialchars($palette['bg']) ?>;
    --member-panel: <?= htmlspecialchars($palette['panel']) ?>;
    --member-accent: <?= htmlspecialchars($palette['accent']) ?>;
    --member-accent-dim: <?= htmlspecialchars($palette['accent']) ?>26;
    --member-text: <?= htmlspecialchars($palette['text']) ?>;
    --member-text-muted: <?= htmlspecialchars($palette['textMuted']) ?>;
    --member-border: <?= htmlspecialchars($palette['border']) ?>;
}
</style>

<div class="member-page">
    <div class="member-profile">
        <div class="member-header">
            <div class="member-avatar">
                <?php if ($member['avatar']): ?>
                    <img src="<?= htmlspecialchars($member['avatar']) ?>" alt="">
                <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <?php endif; ?>
            </div>
            <div class="member-info">
                <h1 class="member-name"><?= htmlspecialchars($member['name']) ?>'s Blog</h1>
            </div>
        </div>

        <div class="member-nav">
            <a href="/team/<?= $member['id'] ?>">About</a>
            <a href="/team/<?= $member['id'] ?>/posts" class="active">Blog</a>
        </div>

        <?php if ($canEdit): ?>
            <div class="member-toolbar">
                <a href="/team/<?= $member['id'] ?>/posts/new" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                    New Post
                </a>
            </div>
        <?php endif; ?>

        <div class="member-content">
            <?php if (empty($posts)): ?>
                <div class="member-empty">
                    <p>No posts yet.</p>
                </div>
            <?php else: ?>
                <div class="post-list">
                    <?php foreach ($posts as $post): ?>
                        <div class="post-list-item">
                            <a href="/team/<?= $member['id'] ?>/posts/<?= $post['slug'] ?>" class="post-list-link">
                                <span class="post-list-title"><?= htmlspecialchars($post['title']) ?></span>
                                <span class="post-list-date"><?= Date::short($post['created_at']) ?></span>
                            </a>
                            <?php if ($canEdit): ?>
                                <div class="post-list-actions">
                                    <a href="/team/<?= $member['id'] ?>/posts/<?= $post['id'] ?>/edit">Edit</a>
                                    <form method="POST" action="/team/<?= $member['id'] ?>/posts/<?= $post['id'] ?>/delete" style="display:inline">
                                        <?= \Api\Core\View::csrfField() ?>
                                        <button type="submit" onclick="return confirm('Delete this post?')">Delete</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>