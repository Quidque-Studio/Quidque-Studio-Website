<?php if (isset($_GET['sent'])): ?>
    <div class="alert alert-success">Newsletter sent!</div>
<?php endif; ?>

<div class="admin-toolbar">
    <a href="/admin/newsletter/create" class="btn btn-primary">New Newsletter</a>
    <a href="/admin/newsletter/subscribers" class="btn">Subscribers (<?= $subscriberCount ?>)</a>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th>Subject</th>
            <th>Created</th>
            <th>Sent</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($newsletters)): ?>
            <tr><td colspan="4">No newsletters yet.</td></tr>
        <?php else: ?>
            <?php foreach ($newsletters as $nl): ?>
                <tr>
                    <td><?= htmlspecialchars($nl['subject']) ?></td>
                    <td><?= $nl['created_at'] ?></td>
                    <td><?= $nl['sent_at'] ?? 'Draft' ?></td>
                    <td class="actions">
                        <?php if (!$nl['sent_at']): ?>
                            <a href="/admin/newsletter/<?= $nl['id'] ?>/edit">Edit</a>
                            <form method="POST" action="/admin/newsletter/<?= $nl['id'] ?>/send" style="display:inline">
                                <?= \Api\Core\View::csrfField() ?>
                                <button type="submit" onclick="return confirm('Send to all subscribers?')">Send</button>
                            </form>
                        <?php endif; ?>
                        <form method="POST" action="/admin/newsletter/<?= $nl['id'] ?>/delete" style="display:inline">
                            <?= \Api\Core\View::csrfField() ?>
                            <button type="submit" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>