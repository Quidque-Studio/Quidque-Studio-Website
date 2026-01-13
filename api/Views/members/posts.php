<?php
use Api\Core\Date;

$customPalette = json_decode($member['color_palette'] ?? '{}', true) ?: [];

$cssVarMap = [
    'bg' => '--bg-color',
    'bgSurface' => '--bg-surface',
    'panel' => '--panel-bg',
    'panelHover' => '--panel-hover',
    'primary' => '--primary',
    'primaryDim' => '--primary-dim',
    'primaryGlow' => '--primary-glow',
    'accent' => '--accent',
    'accentDim' => '--accent-dim',
    'accentGlow' => '--accent-glow',
    'purple' => '--purple',
    'purpleDim' => '--purple-dim',
    'text' => '--text-primary',
    'textSecondary' => '--text-secondary',
    'textMuted' => '--text-muted',
    'border' => '--border-color',
    'borderSubtle' => '--border-subtle',
];
?>

<?php if (!empty($customPalette)): ?>
<style>
:root {
<?php foreach ($customPalette as $key => $value): ?>
<?php if (isset($cssVarMap[$key])): ?>
    <?= $cssVarMap[$key] ?>: <?= htmlspecialchars($value) ?>;
<?php endif; ?>
<?php endforeach; ?>
}
</style>
<?php endif; ?>

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
                <div class="member-posts-timeline">
                    <?php foreach ($posts as $post): ?>
                        <div class="member-post-entry">
                            <a href="/team/<?= $member['id'] ?>/posts/<?= $post['slug'] ?>" class="member-post-card">
                                <div class="member-post-date"><?= Date::short($post['created_at']) ?></div>
                                <div class="member-post-title"><?= htmlspecialchars($post['title']) ?></div>
                            </a>
                            <?php if ($canEdit): ?>
                                <div class="member-post-actions">
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