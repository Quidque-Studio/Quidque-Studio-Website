<?php use Api\Core\Date; ?>

<div class="admin-toolbar">
    <a href="/admin/projects/create" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
        <span class="btn-text">New Project</span>
    </a>
</div>

<div class="admin-table-search">
    <input type="text" id="project-search" placeholder="Search projects..." autocomplete="off">
</div>

<table class="admin-table admin-table-desktop" id="projects-table">
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

<div class="mobile-card-view" id="projects-cards">
    <?php if (empty($projects)): ?>
        <div class="mobile-card" style="text-align: center; color: var(--text-muted); padding: 40px;">
            No projects yet. Create your first project!
        </div>
    <?php else: ?>
        <?php foreach ($projects as $project): ?>
            <div class="mobile-card" data-search="<?= htmlspecialchars(strtolower($project['title'])) ?>">
                <a href="/admin/projects/<?= $project['id'] ?>/edit" class="mobile-card-link">
                    <div class="mobile-card-title"><?= htmlspecialchars($project['title']) ?></div>
                    <div class="mobile-card-meta">
                        <span class="badge <?= $project['status'] === 'in_progress' ? 'badge-primary' : ($project['status'] === 'completed' ? 'badge-primary' : '') ?>" style="font-size: 0.625rem;">
                            <?= ucfirst(str_replace('_', ' ', $project['status'])) ?>
                        </span>
                        <?php if ($project['is_featured']): ?>
                            <span class="badge badge-accent" style="font-size: 0.625rem;">Featured</span>
                        <?php endif; ?>
                        <span style="margin-left: auto;"><?= Date::short($project['updated_at']) ?></span>
                    </div>
                </a>
                <div class="mobile-card-actions">
                    <a href="/admin/projects/<?= $project['id'] ?>/devlogs">Devlogs</a>
                    <a href="/admin/projects/<?= $project['id'] ?>/edit">Edit</a>
                    <form method="POST" action="/admin/projects/<?= $project['id'] ?>/delete" style="display:contents">
                        <?= \Api\Core\View::csrfField() ?>
                        <button type="submit" onclick="return confirm('Delete this project?')">Delete</button>
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

    const searchInput = document.getElementById('project-search');
    const table = document.getElementById('projects-table');
    const cards = document.getElementById('projects-cards');
    
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