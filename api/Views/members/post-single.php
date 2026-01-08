<?php
use Api\Core\Date;
use Api\Core\Str;
use Api\Core\ContentRenderer;

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
$tags = Str::formatTags($post['tags']);
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
        <div class="member-content">
            <div class="post-header">
                <a href="/team/<?= $member['id'] ?>/posts" class="post-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Back to blog
                </a>
                <h1 class="post-title"><?= htmlspecialchars($post['title']) ?></h1>
                <p class="post-meta"><?= Date::long($post['created_at']) ?></p>
                <?php if (!empty($tags)): ?>
                    <div class="post-tags">
                        <?php foreach ($tags as $tag): ?>
                            <span class="post-tag"><?= htmlspecialchars($tag) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?= ContentRenderer::render($post['content']) ?>

            <?php if ($canEdit): ?>
                <div class="post-footer">
                    <a href="/team/<?= $member['id'] ?>/posts/<?= $post['id'] ?>/edit" class="btn">Edit Post</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>