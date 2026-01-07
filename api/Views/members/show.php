<?php
use Api\Core\Str;

$accentColor = $member['accent_color'] ?? '#9d7edb';
$bgColor = $member['bg_color'] ?? '#012a31';
$socialLinks = Str::formatTags($member['social_links']);
$aboutContent = json_decode($member['about_content'] ?? '[]', true);
?>

<style>
.member-page { --member-accent: <?= htmlspecialchars($accentColor) ?>; --member-bg: <?= htmlspecialchars($bgColor) ?>; }
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
                <?php foreach ($aboutContent as $block): ?>
                    <?php if ($block['type'] === 'heading'): ?>
                        <h2><?= htmlspecialchars($block['value']) ?></h2>
                    <?php elseif ($block['type'] === 'text'): ?>
                        <p><?= nl2br(htmlspecialchars($block['value'])) ?></p>
                    <?php elseif ($block['type'] === 'image'): ?>
                        <img src="<?= htmlspecialchars($block['value']) ?>" alt="">
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>