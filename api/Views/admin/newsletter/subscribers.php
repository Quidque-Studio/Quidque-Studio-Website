<?php use Api\Core\Date; ?>

<div class="admin-toolbar">
    <a href="/admin/newsletter" class="btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Back to Newsletters
    </a>
</div>

<div class="admin-table-search">
    <input type="text" id="subscribers-search" placeholder="Search subscribers..." autocomplete="off">
</div>

<table class="admin-table" id="subscribers-table">
    <thead>
        <tr>
            <th>Email</th>
            <th>User</th>
            <th>Subscribed</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($subscribers)): ?>
            <tr><td colspan="4" style="text-align: center; color: var(--text-muted);">No subscribers yet.</td></tr>
        <?php else: ?>
            <?php foreach ($subscribers as $sub): ?>
                <tr>
                    <td><?= htmlspecialchars($sub['email']) ?></td>
                    <td><?= htmlspecialchars($sub['user_name'] ?? '—') ?></td>
                    <td><?= Date::short($sub['subscribed_at']) ?></td>
                    <td>
                        <?php if ($sub['unsubscribed_at']): ?>
                            <span class="badge">Unsubscribed</span>
                        <?php else: ?>
                            <span class="badge badge-primary">Active</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('subscribers-search');
    const table = document.getElementById('subscribers-table');
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