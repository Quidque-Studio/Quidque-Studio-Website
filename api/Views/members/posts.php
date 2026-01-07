<?php
$accentColor = $member['accent_color'] ?? '#9d7edb';
$bgColor = $member['bg_color'] ?? '#012a31';
?>

<style>
.member-page { --member-accent: <?= htmlspecialchars($accentColor) ?>; --member-bg: <?= htmlspecialchars($bgColor) ?>; }
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
                            <span><?= $post['created_at'] ?></span>
                        </a>
                        <?php if ($canEdit): ?>
                            <div class="post-actions">
                                <a href="/team/<?= $member['id'] ?>/posts/<?= $post['id'] ?>/edit">Edit</a>
                                <form method="POST" action="/team/<?= $member['id'] ?>/posts/<?= $post['id'] ?>/delete" style="display:inline">
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