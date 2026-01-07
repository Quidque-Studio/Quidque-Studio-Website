<div class="admin-toolbar">
    <a href="/admin/newsletter" class="btn">Back to Newsletters</a>
</div>

<table class="admin-table">
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
            <tr><td colspan="4">No subscribers yet.</td></tr>
        <?php else: ?>
            <?php foreach ($subscribers as $sub): ?>
                <tr>
                    <td><?= htmlspecialchars($sub['email']) ?></td>
                    <td><?= htmlspecialchars($sub['user_name'] ?? '-') ?></td>
                    <td><?= $sub['subscribed_at'] ?></td>
                    <td><?= $sub['unsubscribed_at'] ? 'Unsubscribed' : 'Active' ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>