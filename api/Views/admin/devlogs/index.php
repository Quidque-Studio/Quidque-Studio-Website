<div class="admin-toolbar">
    <a href="/admin/projects/<?= $project['id'] ?>/devlogs/create" class="btn btn-primary">New Devlog</a>
    <a href="/admin/projects/<?= $project['id'] ?>/edit" class="btn">Back to Project</a>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($devlogs)): ?>
            <tr><td colspan="4">No devlogs yet.</td></tr>
        <?php else: ?>
            <?php foreach ($devlogs as $devlog): ?>
                <tr>
                    <td><?= htmlspecialchars($devlog['title']) ?></td>
                    <td><?= htmlspecialchars($devlog['author_name'] ?? 'Unknown') ?></td>
                    <td><?= $devlog['created_at'] ?></td>
                    <td class="actions">
                        <a href="/admin/projects/<?= $project['id'] ?>/devlogs/<?= $devlog['id'] ?>/edit">Edit</a>
                        <form method="POST" action="/admin/projects/<?= $project['id'] ?>/devlogs/<?= $devlog['id'] ?>/delete" style="display:inline">
                            <?= \Api\Core\View::csrfField() ?>
                            <button type="submit" onclick="return confirm('Delete this devlog?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>