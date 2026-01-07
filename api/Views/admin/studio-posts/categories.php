<div class="admin-toolbar">
    <a href="/admin/studio-posts" class="btn">Back to Posts</a>
</div>

<div class="form-section">
    <h2>Add Category</h2>
    <form method="POST" action="/admin/studio-posts/categories" class="inline-form">
        <?= \Api\Core\View::csrfField() ?>
        <input type="text" name="name" placeholder="Category name" required>
        <button type="submit" class="btn btn-primary">Add</button>
    </form>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Slug</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($categories)): ?>
            <tr><td colspan="3">No categories yet.</td></tr>
        <?php else: ?>
            <?php foreach ($categories as $cat): ?>
                <tr>
                    <td>
                        <form method="POST" action="/admin/studio-posts/categories/<?= $cat['id'] ?>" class="inline-edit-form">
                            <?= \Api\Core\View::csrfField() ?>
                            <input type="text" name="name" value="<?= htmlspecialchars($cat['name']) ?>" class="inline-input">
                            <button type="submit" class="inline-save">Save</button>
                        </form>
                    </td>
                    <td><?= htmlspecialchars($cat['slug']) ?></td>
                    <td class="actions">
                        <form method="POST" action="/admin/studio-posts/categories/<?= $cat['id'] ?>/delete" style="display:inline">
                            <?= \Api\Core\View::csrfField() ?>
                            <button type="submit" onclick="return confirm('Delete this category?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>