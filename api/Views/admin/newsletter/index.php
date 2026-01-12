<?php use Api\Core\Date; ?>

<?php if (isset($_GET['sent'])): ?>
    <div class="alert alert-success">Newsletter sent successfully!</div>
<?php endif; ?>

<div class="admin-toolbar">
    <a href="/admin/newsletter/create" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
        New Newsletter
    </a>
    <a href="/admin/newsletter/subscribers" class="btn">Subscribers (<?= $subscriberCount ?>)</a>
</div>

<div class="admin-table-search">
    <input type="text" id="newsletter-search" placeholder="Search newsletters..." autocomplete="off">
</div>

<table class="admin-table" id="newsletter-table">
    <thead>
        <tr>
            <th>Subject</th>
            <th>Created</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($newsletters)): ?>
            <tr><td colspan="4" style="text-align: center; color: var(--text-muted);">No newsletters yet.</td></tr>
        <?php else: ?>
            <?php foreach ($newsletters as $nl): ?>
                <tr data-href="<?= $nl['sent_at'] ? '/admin/newsletter/' . $nl['id'] . '/preview' : '/admin/newsletter/' . $nl['id'] . '/edit' ?>" class="clickable-row">
                    <td><?= htmlspecialchars($nl['subject']) ?></td>
                    <td><?= Date::short($nl['created_at']) ?></td>
                    <td>
                        <?php if ($nl['sent_at']): ?>
                            <span class="badge badge-primary">Sent <?= Date::short($nl['sent_at']) ?></span>
                        <?php else: ?>
                            <span class="badge">Draft</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions" onclick="event.stopPropagation()">
                        <?php if (!$nl['sent_at']): ?>
                            <a href="/admin/newsletter/<?= $nl['id'] ?>/edit">Edit</a>
                            <a href="/admin/newsletter/<?= $nl['id'] ?>/preview">Preview</a>
                            <form method="POST" action="/admin/newsletter/<?= $nl['id'] ?>/send" style="display:inline">
                                <?= \Api\Core\View::csrfField() ?>
                                <button type="submit" onclick="return confirm('Send to all subscribers?')">Send</button>
                            </form>
                        <?php else: ?>
                            <a href="/admin/newsletter/<?= $nl['id'] ?>/preview">View</a>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', function() {
            window.location.href = this.dataset.href;
        });
    });

    const searchInput = document.getElementById('newsletter-search');
    const table = document.getElementById('newsletter-table');
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