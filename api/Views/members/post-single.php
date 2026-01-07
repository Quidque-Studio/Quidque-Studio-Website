<?php
use Api\Core\Date;
use Api\Core\Str;
use Api\Core\ContentRenderer;

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
$tags = Str::formatTags($post['tags']);
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
        <a href="/team/<?= $member['id'] ?>/posts">← Back to blog</a>
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <p class="post-meta"><?= Date::long($post['created_at']) ?></p>
        <?php if (!empty($tags)): ?>
            <div class="post-tags">
                <?php foreach ($tags as $tag): ?>
                    <span class="tag"><?= htmlspecialchars($tag) ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="member-content post-content">
        <?= ContentRenderer::render($post['content']) ?>
    </div>

    <?php if ($canEdit): ?>
        <div class="post-actions">
            <a href="/team/<?= $member['id'] ?>/posts/<?= $post['id'] ?>/edit" class="btn">Edit</a>
        </div>
    <?php endif; ?>
</div>