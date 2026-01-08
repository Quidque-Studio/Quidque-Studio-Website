<div class="projects-header">
    <div class="page-header" style="margin-bottom: 0;">
        <h1 class="page-title">Projects</h1>
        <p class="page-subtitle">Explore our games, tools, and experiments</p>
    </div>
</div>

<div class="projects-grid">
    <?php if (empty($projects)): ?>
        <div class="projects-empty">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20 17a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3.9a2 2 0 0 1-1.69-.9l-.81-1.2a2 2 0 0 0-1.67-.9H8a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2Z"/><path d="M2 8v11a2 2 0 0 0 2 2h14"/></svg>
            <p>No projects yet. Check back soon!</p>
        </div>
    <?php else: ?>
        <?php foreach ($projects as $project): ?>
            <a href="/projects/<?= htmlspecialchars($project['slug']) ?>" class="project-card">
                <?php if ($project['thumbnail']): ?>
                    <img src="<?= htmlspecialchars($project['thumbnail']) ?>" alt="" class="project-card-thumb">
                <?php else: ?>
                    <div class="project-card-thumb project-card-thumb-empty">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                    </div>
                <?php endif; ?>
                <div class="project-card-body">
                    <h2><?= htmlspecialchars($project['title']) ?></h2>
                    <span class="status-badge status-<?= $project['status'] ?>">
                        <?= ucfirst(str_replace('_', ' ', $project['status'])) ?>
                    </span>
                    <?php if ($project['description']): ?>
                        <p><?= htmlspecialchars(mb_strimwidth($project['description'], 0, 120, '...')) ?></p>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include BASE_PATH . '/api/Views/partials/pagination.php'; ?>