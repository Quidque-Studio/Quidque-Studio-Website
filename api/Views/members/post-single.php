<?php
use Api\Core\Date;
use Api\Core\Str;

$accentColor = $member['accent_color'] ?? '#9d7edb';
$bgColor = $member['bg_color'] ?? '#012a31';
$content = json_decode($post['content'] ?? '[]', true);
$tags = Str::formatTags($post['tags']);
?>

<style>
.member-page { --member-accent: <?= htmlspecialchars($accentColor) ?>; --member-bg: <?= htmlspecialchars($bgColor) ?>; }
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
        <?php foreach ($content as $block): ?>
            <?php if ($block['type'] === 'heading'): ?>
                <h2><?= htmlspecialchars($block['value']) ?></h2>
            <?php elseif ($block['type'] === 'text'): ?>
                <p><?= nl2br(htmlspecialchars($block['value'])) ?></p>
            <?php elseif ($block['type'] === 'image'): ?>
                <img src="<?= htmlspecialchars($block['value']) ?>" alt="">
            <?php elseif ($block['type'] === 'code'): ?>
                <pre><code><?= htmlspecialchars($block['value']) ?></code></pre>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <?php if ($canEdit): ?>
        <div class="post-actions">
            <a href="/team/<?= $member['id'] ?>/posts/<?= $post['id'] ?>/edit" class="btn">Edit</a>
        </div>
    <?php endif; ?>
</div>