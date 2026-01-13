<?php
use Api\Core\Date;
use Api\Core\Str;
use Api\Core\ContentRenderer;

$customPalette = json_decode($member['color_palette'] ?? '{}', true) ?: [];
$tags = Str::formatTags($post['tags']);

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