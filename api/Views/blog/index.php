<?php
use Api\Core\ContentRenderer;
use Api\Core\Date;
?>

<div class="page-header">
    <h1><?= $currentCategory ? htmlspecialchars($currentCategory['name']) : 'Blog' ?></h1>
</div>

<?php if (!empty($categories)): ?>
    <nav class="category-nav">
        <a href="/blog" <?= !$currentCategory ? 'class="active"' : '' ?>>All</a>
        <?php foreach ($categories as $cat): ?>
            <a href="/blog?category=<?= htmlspecialchars($cat['slug']) ?>" <?= $currentCategory && $currentCategory['id'] === $cat['id'] ? 'class="active"' : '' ?>><?= htmlspecialchars($cat['name']) ?></a>
        <?php endforeach; ?>
    </nav>
<?php endif; ?>

<div class="blog-list">
    <?php if (empty($posts)): ?>
        <p>No posts yet.</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <article class="blog-card">
                <a href="/blog/<?= htmlspecialchars($post['slug']) ?>">
                    <h2><?= htmlspecialchars($post['title']) ?></h2>
                </a>
                <div class="blog-card-meta">
                    <span><?= Date::short($post['created_at']) ?></span>
                    <?php if ($post['category_name']): ?>
                        <a href="/blog?category=<?= htmlspecialchars($post['category_slug']) ?>" class="category"><?= htmlspecialchars($post['category_name']) ?></a>
                    <?php endif; ?>
                </div>
                <p><?= ContentRenderer::excerpt($post['content'], 200) ?></p>
                <a href="/blog/<?= htmlspecialchars($post['slug']) ?>" class="read-more">Read more</a>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php
$paginationParams = $currentCategory ? ['category' => $currentCategory['slug']] : [];
include BASE_PATH . '/api/Views/partials/pagination.php';
?>