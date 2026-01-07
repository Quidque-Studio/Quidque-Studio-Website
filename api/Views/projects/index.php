<?php use Api\Core\ContentRenderer; ?>

<div class="page-header">
    <h1>Projects</h1>
</div>

<div class="projects-grid">
    <?php if (empty($projects)): ?>
        <p>No projects yet.</p>
    <?php else: ?>
        <?php foreach ($projects as $project): ?>
            <a href="/projects/<?= htmlspecialchars($project['slug']) ?>" class="project-card">
                <?php if ($project['thumbnail']): ?>
                    <img src="<?= htmlspecialchars($project['thumbnail']) ?>" alt="" class="project-card-thumb">
                <?php else: ?>
                    <div class="project-card-thumb project-card-thumb-empty"></div>
                <?php endif; ?>
                <div class="project-card-body">
                    <h2><?= htmlspecialchars($project['title']) ?></h2>
                    <span class="status-badge status-<?= $project['status'] ?>"><?= ucfirst(str_replace('_', ' ', $project['status'])) ?></span>
                    <?php if ($project['description']): ?>
                        <p><?= htmlspecialchars(substr($project['description'], 0, 120)) ?>...</p>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include BASE_PATH . '/api/Views/partials/pagination.php'; ?>