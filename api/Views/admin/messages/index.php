<?php use Api\Core\Date; ?>

<div class="admin-table-search">
    <input type="text" id="messages-search" placeholder="Search messages..." autocomplete="off">
</div>

<table class="admin-table" id="messages-table">
    <thead>
        <tr>
            <th>Subject</th>
            <th>From</th>
            <th>Messages</th>
            <th>Last Update</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($conversations)): ?>
            <tr><td colspan="5" style="text-align: center; color: var(--text-muted);">No messages yet.</td></tr>
        <?php else: ?>
            <?php foreach ($conversations as $conv): ?>
                <tr data-href="/admin/messages/<?= $conv['id'] ?>" class="clickable-row">
                    <td><?= htmlspecialchars($conv['subject']) ?></td>
                    <td><?= htmlspecialchars($conv['user_name']) ?></td>
                    <td><?= $conv['message_count'] ?></td>
                    <td><?= Date::relative($conv['updated_at']) ?></td>
                    <td class="actions" onclick="event.stopPropagation()">
                        <a href="/admin/messages/<?= $conv['id'] ?>">View</a>
                        <form method="POST" action="/admin/messages/<?= $conv['id'] ?>/delete" style="display:inline">
                            <?= \Api\Core\View::csrfField() ?>
                            <button type="submit" onclick="return confirm('Delete this conversation and all messages?')">Delete</button>
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

    const searchInput = document.getElementById('messages-search');
    const table = document.getElementById('messages-table');
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