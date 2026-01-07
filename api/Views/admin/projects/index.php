<div class="admin-toolbar">
    <a href="/admin/projects/create" class="btn btn-primary">New Project</a>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Status</th>
            <th>Featured</th>
            <th>Updated</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($projects)): ?>
            <tr><td colspan="5">No projects yet.</td></tr>
        <?php else: ?>
            <?php foreach ($projects as $project): ?>
                <tr>
                    <td><?= htmlspecialchars($project['title']) ?></td>
                    <td><?= htmlspecialchars($project['status']) ?></td>
                    <td><?= $project['is_featured'] ? 'Yes' : 'No' ?></td>
                    <td><?= $project['updated_at'] ?></td>
                    <td class="actions">
                        <a href="/admin/projects/<?= $project['id'] ?>/devlogs">Devlogs</a>
                        <a href="/admin/projects/<?= $project['id'] ?>/edit">Edit</a>
                        <form method="POST" action="/admin/projects/<?= $project['id'] ?>/delete" style="display:inline">
                            <button type="submit" onclick="return confirm('Delete this project?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>