<div class="admin-toolbar">
    <a href="/admin/studio-posts/create" class="btn btn-primary">New Post</a>
    <a href="/admin/studio-posts/categories" class="btn">Categories</a>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($posts)): ?>
            <tr><td colspan="4">No posts yet.</td></tr>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= htmlspecialchars($post['title']) ?></td>
                    <td><?= htmlspecialchars($post['category_name'] ?? '-') ?></td>
                    <td><?= $post['created_at'] ?></td>
                    <td class="actions">
                        <a href="/admin/studio-posts/<?= $post['id'] ?>/edit">Edit</a>
                        <form method="POST" action="/admin/studio-posts/<?= $post['id'] ?>/delete" style="display:inline">
                            <?= \Api\Core\View::csrfField() ?>
                            <button type="submit" onclick="return confirm('Delete this post?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>