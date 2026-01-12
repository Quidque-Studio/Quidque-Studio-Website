<?php use Api\Core\Date; ?>

<?php if (isset($_GET['error']) && $_GET['error'] === 'self_delete'): ?>
    <div class="alert alert-error">You cannot delete yourself.</div>
<?php endif; ?>

<div class="admin-table-search">
    <input type="text" id="users-search" placeholder="Search users..." autocomplete="off">
</div>

<table class="admin-table" id="users-table">
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
            <tr data-href="/admin/users/<?= $u['id'] ?>/edit" class="clickable-row">
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td>
                    <span class="badge <?= $u['role'] === 'team_member' ? 'badge-primary' : '' ?>">
                        <?= $u['role'] === 'team_member' ? 'Team' : 'User' ?>
                    </span>
                </td>
                <td><?= Date::short($u['created_at']) ?></td>
                <td class="actions" onclick="event.stopPropagation()">
                    <a href="/admin/users/<?= $u['id'] ?>/edit">Edit</a>
                    <?php if ($u['id'] !== $user['id']): ?>
                        <form method="POST" action="/admin/users/<?= $u['id'] ?>/delete" style="display:inline">
                            <?= \Api\Core\View::csrfField() ?>
                            <button type="submit" onclick="return confirm('Delete this user?')">Delete</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', function() {
            window.location.href = this.dataset.href;
        });
    });

    const searchInput = document.getElementById('users-search');
    const table = document.getElementById('users-table');
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