<?php use Api\Core\Date; ?>

<table class="admin-table">
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
                <tr>
                    <td><?= htmlspecialchars($conv['subject']) ?></td>
                    <td><?= htmlspecialchars($conv['user_name']) ?></td>
                    <td><?= $conv['message_count'] ?></td>
                    <td><?= Date::relative($conv['updated_at']) ?></td>
                    <td class="actions">
                        <a href="/admin/messages/<?= $conv['id'] ?>">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>