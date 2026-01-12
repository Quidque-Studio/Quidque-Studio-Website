<?php use Api\Core\Date; ?>

<div class="admin-toolbar">
    <a href="/admin/projects/create" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
        New Project
    </a>
</div>

<div class="admin-table-search">
    <input type="text" id="project-search" placeholder="Search projects..." autocomplete="off">
</div>

<table class="admin-table" id="projects-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Status</th>
            <th>Featured</th>
            <th>Updated</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($projects)): ?>
            <tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 40px;">No projects yet. Create your first project!</td></tr>
        <?php else: ?>
            <?php foreach ($projects as $project): ?>
                <tr data-href="/admin/projects/<?= $project['id'] ?>/edit" class="clickable-row">
                    <td style="font-weight: 500;"><?= htmlspecialchars($project['title']) ?></td>
                    <td>
                        <span class="badge <?= $project['status'] === 'in_progress' ? 'badge-primary' : ($project['status'] === 'completed' ? 'badge-primary' : '') ?>">
                            <?= ucfirst(str_replace('_', ' ', $project['status'])) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($project['is_featured']): ?>
                            <span class="badge badge-accent">Featured</span>
                        <?php else: ?>
                            <span style="color: var(--text-muted);">—</span>
                        <?php endif; ?>
                    </td>
                    <td><?= Date::short($project['updated_at']) ?></td>
                    <td class="actions" onclick="event.stopPropagation()">
                        <a href="/admin/projects/<?= $project['id'] ?>/devlogs">Devlogs</a>
                        <a href="/admin/projects/<?= $project['id'] ?>/edit">Edit</a>
                        <form method="POST" action="/admin/projects/<?= $project['id'] ?>/delete" style="display:inline">
                            <?= \Api\Core\View::csrfField() ?>
                            <button type="submit" onclick="return confirm('Delete this project?')">Delete</button>
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

    const searchInput = document.getElementById('project-search');
    const table = document.getElementById('projects-table');
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