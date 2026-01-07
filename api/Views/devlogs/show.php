<?php use Api\Core\ContentRenderer; ?>

<article class="devlog-single">
    <header class="devlog-header">
        <a href="/projects/<?= htmlspecialchars($project['slug']) ?>" class="back-link">← <?= htmlspecialchars($project['title']) ?></a>
        <h1><?= htmlspecialchars($devlog['title']) ?></h1>
        <div class="devlog-meta">
            <span><?= date('F j, Y', strtotime($devlog['created_at'])) ?></span>
            <?php if ($devlog['author_name']): ?>
                <span>by <?= htmlspecialchars($devlog['author_name']) ?></span>
            <?php endif; ?>
        </div>
        <?php
        $tags = json_decode($devlog['tags'] ?? '[]', true);
        if (!empty($tags)):
        ?>
            <div class="devlog-tags">
                <?php foreach ($tags as $tag): ?>
                    <span class="tag"><?= htmlspecialchars($tag) ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </header>

    <div class="devlog-content">
        <?= ContentRenderer::render($devlog['content']) ?>
    </div>
</article>