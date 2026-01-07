<?php if (isset($_GET['error']) && $_GET['error'] === 'self_delete'): ?>
    <div class="alert alert-error">You cannot delete yourself.</div>
<?php endif; ?>

<table class="admin-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= $u['role'] ?></td>
                <td><?= $u['created_at'] ?></td>
                <td class="actions">
                    <a href="/admin/users/<?= $u['id'] ?>/edit">Edit</a>
                    <?php if ($u['id'] !== $user['id']): ?>
                        <form method="POST" action="/admin/users/<?= $u['id'] ?>/delete" style="display:inline">
                            <button type="submit" onclick="return confirm('Delete this user?')">Delete</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>