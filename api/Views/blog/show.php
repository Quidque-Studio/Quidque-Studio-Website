<?php
use Api\Core\ContentRenderer;
use Api\Core\Date;
use Api\Core\Str;
?>

<article class="article-container">
    <header class="article-header">
        <nav class="article-breadcrumb">
            <a href="/blog">News</a>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            <?php if (!empty($post['category_name'])): ?>
                <a href="/blog?category=<?= htmlspecialchars($post['category_slug']) ?>"><?= htmlspecialchars($post['category_name']) ?></a>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            <?php endif; ?>
            <span>Article</span>
        </nav>
        
        <div class="article-meta">
            <span class="article-date"><?= Date::long($post['created_at']) ?></span>
            <?php if (!empty($post['category_name'])): ?>
                <a href="/blog?category=<?= htmlspecialchars($post['category_slug']) ?>" class="article-category">
                    <?= htmlspecialchars($post['category_name']) ?>
                </a>
            <?php endif; ?>
        </div>
        
        <h1 class="article-title"><?= htmlspecialchars($post['title']) ?></h1>
        
        <?php $tags = Str::formatTags($post['tags']); ?>
        <?php if (!empty($tags)): ?>
        <div class="article-tags">
            <?php foreach ($tags as $tag): ?>
                <span class="article-tag"><?= htmlspecialchars($tag) ?></span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </header>

    <div class="article-content">
        <?= ContentRenderer::render($post['content']) ?>
    </div>
</article>