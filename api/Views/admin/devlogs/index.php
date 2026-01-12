<?php use Api\Core\Date; ?>

<?php
$referrer = $_GET['from'] ?? 'list';
$backUrl = $referrer === 'edit' ? "/admin/projects/{$project['id']}/edit" : "/admin/projects";
$backText = $referrer === 'edit' ? "Back to Project" : "Back to Projects";
?>

<div class="admin-toolbar">
    <a href="/admin/projects/<?= $project['id'] ?>/devlogs/create" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
        New Devlog
    </a>
    <a href="<?= $backUrl ?>" class="btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        <?= $backText ?>
    </a>
</div>

<div class="form-section" style="margin-bottom: 24px; padding: 16px 20px;">
    <p style="margin: 0; color: var(--text-secondary);">
        Managing devlogs for <strong style="color: var(--text-primary);"><?= htmlspecialchars($project['title']) ?></strong>
    </p>
</div>

<div class="admin-table-search">
    <input type="text" id="devlog-search" placeholder="Search devlogs..." autocomplete="off">
</div>

<table class="admin-table" id="devlogs-table">
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
            <tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 40px;">No devlogs yet. Document your progress!</td></tr>
        <?php else: ?>
            <?php foreach ($devlogs as $devlog): ?>
                <tr data-href="/admin/projects/<?= $project['id'] ?>/devlogs/<?= $devlog['id'] ?>/edit" class="clickable-row">
                    <td style="font-weight: 500;"><?= htmlspecialchars($devlog['title']) ?></td>
                    <td><?= htmlspecialchars($devlog['author_name'] ?? 'Unknown') ?></td>
                    <td><?= Date::short($devlog['created_at']) ?></td>
                    <td class="actions" onclick="event.stopPropagation()">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', function() {
            window.location.href = this.dataset.href;
        });
    });

    const searchInput = document.getElementById('devlog-search');
    const table = document.getElementById('devlogs-table');
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