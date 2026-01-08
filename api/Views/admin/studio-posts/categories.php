<div class="editor-page">
    <div class="editor-header">
        <a href="/admin/studio-posts" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Back to Posts
        </a>
        <h1 class="editor-title">Manage Categories</h1>
    </div>

    <div class="category-manager">
        <form method="POST" action="/admin/studio-posts/categories" class="category-form">
            <?= \Api\Core\View::csrfField() ?>
            <input type="text" name="name" placeholder="New category name" required>
            <button type="submit" class="btn btn-primary">Add Category</button>
        </form>

        <?php if (empty($categories)): ?>
            <div class="form-section" style="text-align: center; color: var(--text-muted); padding: 40px;">
                No categories yet. Create your first category above.
            </div>
        <?php else: ?>
            <div class="category-list">
                <?php foreach ($categories as $cat): ?>
                    <div class="category-item">
                        <form method="POST" action="/admin/studio-posts/categories/<?= $cat['id'] ?>" style="display: contents;">
                            <?= \Api\Core\View::csrfField() ?>
                            <input type="text" name="name" value="<?= htmlspecialchars($cat['name']) ?>">
                            <span class="category-slug"><?= htmlspecialchars($cat['slug']) ?></span>
                            <div class="category-actions">
                                <button type="submit" class="save">Save</button>
                            </div>
                        </form>
                        <form method="POST" action="/admin/studio-posts/categories/<?= $cat['id'] ?>/delete" style="display: contents;">
                            <?= \Api\Core\View::csrfField() ?>
                            <div class="category-actions">
                                <button type="submit" class="delete" onclick="return confirm('Delete this category?')">Delete</button>
                            </div>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>