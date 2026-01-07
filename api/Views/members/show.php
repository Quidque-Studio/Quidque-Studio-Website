<?php
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

$socialLinks = json_decode($member['social_links'] ?? '[]', true) ?: [];
$aboutContent = json_decode($member['about_content'] ?? '[]', true) ?: [];
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
        <?php if ($member['avatar']): ?>
            <img src="<?= htmlspecialchars($member['avatar']) ?>" alt="Avatar" class="member-avatar">
        <?php endif; ?>
        <h1><?= htmlspecialchars($member['name']) ?></h1>
        <?php if ($member['role_title']): ?>
            <p class="member-role"><?= htmlspecialchars($member['role_title']) ?></p>
        <?php endif; ?>
        <?php if ($member['short_bio']): ?>
            <p class="member-bio"><?= htmlspecialchars($member['short_bio']) ?></p>
        <?php endif; ?>
        <?php if (!empty($socialLinks)): ?>
            <div class="member-socials">
                <?php foreach ($socialLinks as $link): ?>
                    <a href="<?= htmlspecialchars($link['url']) ?>" target="_blank"><?= htmlspecialchars($link['platform']) ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="member-nav">
            <a href="/team/<?= $member['id'] ?>" class="active">About</a>
            <a href="/team/<?= $member['id'] ?>/posts">Blog</a>
        </div>
    </div>

    <div class="member-content">
        <?php if ($canEdit): ?>
            <form method="POST" action="/team/<?= $member['id'] ?>/about" class="about-edit-form">
                <?= \Api\Core\View::csrfField() ?>
                <div id="block-editor" class="block-editor">
                    <div id="blocks-container"></div>
                    <div class="block-add">
                        <select id="block-type">
                            <option value="text">Text</option>
                            <option value="heading">Heading</option>
                            <option value="image">Image</option>
                            <option value="code">Code</option>
                            <option value="quote">Quote</option>
                            <option value="list">List</option>
                            <option value="callout">Callout</option>
                            <option value="video">Video</option>
                            <option value="divider">Divider</option>
                        </select>
                        <button type="button" id="add-block" class="btn">Add Block</button>
                    </div>
                </div>
                <input type="hidden" name="about_content" id="content-json" value="<?= htmlspecialchars($member['about_content'] ?? '[]') ?>">
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
            <script src="/js/admin/block-editor.js"></script>
        <?php else: ?>
            <div class="about-content">
                <?= ContentRenderer::render($member['about_content']) ?>
            </div>
        <?php endif; ?>
    </div>
</div>