<?php
use Api\Core\ContentRenderer;
use Api\Core\Date;
?>

<div class="blog-header">
    <div class="page-header" style="margin-bottom: 0;">
        <h1 class="page-title"><?= $currentCategory ? htmlspecialchars($currentCategory['name']) : 'Studio News' ?></h1>
        <p class="page-subtitle">Updates, announcements, and behind-the-scenes</p>
    </div>
    
    <?php if (!empty($categories)): ?>
    <div class="blog-filters">
        <div class="category-dropdown">
            <select id="category-select" onchange="window.location.href = this.value ? '/blog?category=' + this.value : '/blog'">
                <option value="" <?= !$currentCategory ? 'selected' : '' ?>>All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['slug']) ?>" <?= $currentCategory && $currentCategory['id'] === $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="blog-grid">
    <?php if (empty($posts)): ?>
        <div class="blog-empty">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
            <p>No posts yet. Check back soon!</p>
        </div>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
        <article class="blog-card">
            <div class="blog-card-body">
                <div class="blog-card-meta">
                    <span class="blog-card-date"><?= Date::short($post['created_at']) ?></span>
                    <?php if ($post['category_name']): ?>
                        <a href="/blog?category=<?= htmlspecialchars($post['category_slug']) ?>" class="blog-card-category">
                            <?= htmlspecialchars($post['category_name']) ?>
                        </a>
                    <?php endif; ?>
                </div>
                <a href="/blog/<?= htmlspecialchars($post['slug']) ?>" class="blog-card-title">
                    <?= htmlspecialchars($post['title']) ?>
                </a>
                <a href="/blog/<?= htmlspecialchars($post['slug']) ?>" class="blog-card-excerpt">
                    <?= ContentRenderer::excerpt($post['content'], 150) ?>
                </a>
                <div class="blog-card-footer">
                    <a href="/blog/<?= htmlspecialchars($post['slug']) ?>" class="read-more">
                        Read more
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php
$paginationParams = $currentCategory ? ['category' => $currentCategory['slug']] : [];
include BASE_PATH . '/api/Views/partials/pagination.php';
?>