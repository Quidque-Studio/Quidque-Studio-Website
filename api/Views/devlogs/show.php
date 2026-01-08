<?php
use Api\Core\ContentRenderer;
use Api\Core\Date;
use Api\Core\Str;
?>

<article class="devlog-container">
    <header class="devlog-header">
        <nav class="devlog-breadcrumb">
            <a href="/projects">Projects</a>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            <a href="/projects/<?= htmlspecialchars($project['slug']) ?>"><?= htmlspecialchars($project['title']) ?></a>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            <span>Devlog</span>
        </nav>
        
        <a href="/projects/<?= htmlspecialchars($project['slug']) ?>" class="devlog-project-badge">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20 17a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3.9a2 2 0 0 1-1.69-.9l-.81-1.2a2 2 0 0 0-1.67-.9H8a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2Z"/><path d="M2 8v11a2 2 0 0 0 2 2h14"/></svg>
            <span><?= htmlspecialchars($project['title']) ?></span>
        </a>
        
        <div class="devlog-meta">
            <span class="devlog-date"><?= Date::long($devlog['created_at']) ?></span>
            <?php if ($devlog['author_name']): ?>
            <span class="devlog-author">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <?= htmlspecialchars($devlog['author_name']) ?>
            </span>
            <?php endif; ?>
        </div>
        
        <h1 class="devlog-title"><?= htmlspecialchars($devlog['title']) ?></h1>
        
        <?php $tags = Str::formatTags($devlog['tags']); ?>
        <?php if (!empty($tags)): ?>
        <div class="devlog-tags">
            <?php foreach ($tags as $tag): ?>
                <span class="devlog-tag"><?= htmlspecialchars($tag) ?></span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </header>

    <div class="devlog-content">
        <?= ContentRenderer::render($devlog['content']) ?>
    </div>
</article>