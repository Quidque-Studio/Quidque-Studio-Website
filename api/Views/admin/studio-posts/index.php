<?php use Api\Core\Date; ?>

<div class="admin-toolbar">
    <a href="/admin/studio-posts/create" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
        New Post
    </a>
    <a href="/admin/studio-posts/categories" class="btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><path d="M7 7h.01"/></svg>
        Manage Categories
    </a>
</div>

<div class="admin-table-search">
    <input type="text" id="posts-search" placeholder="Search posts..." autocomplete="off">
</div>

<table class="admin-table" id="posts-table">
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
            <tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 40px;">No posts yet. Share some news!</td></tr>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <tr data-href="/admin/studio-posts/<?= $post['id'] ?>/edit" class="clickable-row">
                    <td style="font-weight: 500;"><?= htmlspecialchars($post['title']) ?></td>
                    <td>
                        <?php if ($post['category_name']): ?>
                            <span class="badge badge-purple"><?= htmlspecialchars($post['category_name']) ?></span>
                        <?php else: ?>
                            <span style="color: var(--text-muted);">—</span>
                        <?php endif; ?>
                    </td>
                    <td><?= Date::short($post['created_at']) ?></td>
                    <td class="actions" onclick="event.stopPropagation()">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', function() {
            window.location.href = this.dataset.href;
        });
    });

    const searchInput = document.getElementById('posts-search');
    const table = document.getElementById('posts-table');
    if (searchInput && table) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            table.querySelectorAll('tbody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }
});
</script>