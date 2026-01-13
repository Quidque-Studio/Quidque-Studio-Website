<?php use Api\Core\Date; ?>

<?php
$referrer = $_GET['from'] ?? 'list';
$backUrl = $referrer === 'edit' ? "/admin/projects/{$project['id']}/edit" : "/admin/projects";
$backText = $referrer === 'edit' ? "Back to Project" : "Back to Projects";
?>

<div class="admin-toolbar">
    <a href="/admin/projects/<?= $project['id'] ?>/devlogs/create" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
        <span class="btn-text">New Devlog</span>
    </a>
    <a href="<?= $backUrl ?>" class="btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        <span class="btn-text"><?= $backText ?></span>
    </a>
</div>

<div class="form-section" style="margin-bottom: 16px; padding: 12px 16px;">
    <p style="margin: 0; color: var(--text-secondary); font-size: var(--text-sm);">
        Managing devlogs for <strong style="color: var(--text-primary);"><?= htmlspecialchars($project['title']) ?></strong>
    </p>
</div>

<div class="admin-table-search">
    <input type="text" id="devlog-search" placeholder="Search devlogs..." autocomplete="off">
</div>

<table class="admin-table admin-table-desktop" id="devlogs-table">
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

<div class="mobile-card-view" id="devlogs-cards">
    <?php if (empty($devlogs)): ?>
        <div class="mobile-card" style="text-align: center; color: var(--text-muted); padding: 40px;">
            No devlogs yet. Document your progress!
        </div>
    <?php else: ?>
        <?php foreach ($devlogs as $devlog): ?>
            <div class="mobile-card" data-search="<?= htmlspecialchars(strtolower($devlog['title'] . ' ' . ($devlog['author_name'] ?? ''))) ?>">
                <div class="mobile-card-title"><?= htmlspecialchars($devlog['title']) ?></div>
                <div class="mobile-card-meta">
                    <?= htmlspecialchars($devlog['author_name'] ?? 'Unknown') ?> · <?= Date::short($devlog['created_at']) ?>
                </div>
                <div class="mobile-card-actions">
                    <a href="/admin/projects/<?= $project['id'] ?>/devlogs/<?= $devlog['id'] ?>/edit">Edit</a>
                    <form method="POST" action="/admin/projects/<?= $project['id'] ?>/devlogs/<?= $devlog['id'] ?>/delete" style="display:contents">
                        <?= \Api\Core\View::csrfField() ?>
                        <button type="submit" onclick="return confirm('Delete this devlog?')">Delete</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', function() {
            window.location.href = this.dataset.href;
        });
    });

    const searchInput = document.getElementById('devlog-search');
    const table = document.getElementById('devlogs-table');
    const cards = document.getElementById('devlogs-cards');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            
            if (table) {
                table.querySelectorAll('tbody tr').forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            }
            
            if (cards) {
                cards.querySelectorAll('.mobile-card[data-search]').forEach(card => {
                    const text = card.dataset.search;
                    card.style.display = text.includes(query) ? '' : 'none';
                });
            }
        });
    }
});
</script>