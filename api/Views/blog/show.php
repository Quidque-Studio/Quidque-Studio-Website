<?php
use Api\Core\ContentRenderer;
use Api\Core\Date;
use Api\Core\Str;
?>

<article class="blog-single">
    <header class="blog-header">
        <a href="/blog" class="back-link">← Back to Blog</a>
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <div class="blog-meta">
            <span><?= Date::long($post['created_at']) ?></span>
            <?php if (!empty($post['category_name'])): ?>
                <span class="category"><?= htmlspecialchars($post['category_name']) ?></span>
            <?php endif; ?>
        </div>
        <?php $tags = Str::formatTags($post['tags']); ?>
        <?php if (!empty($tags)): ?>
            <div class="blog-tags">
                <?php foreach ($tags as $tag): ?>
                    <span class="tag"><?= htmlspecialchars($tag) ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </header>

    <div class="blog-content">
        <?= ContentRenderer::render($post['content']) ?>
    </div>
</article>