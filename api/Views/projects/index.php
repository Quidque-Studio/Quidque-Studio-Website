<div class="projects-header">
    <div class="page-header" style="margin-bottom: 0;">
        <h1 class="page-title">Projects</h1>
        <p class="page-subtitle">Explore our games, tools, and experiments</p>
    </div>
</div>

<?php if ($totalProjects === 0): ?>
    <div class="projects-empty-page">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20 17a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3.9a2 2 0 0 1-1.69-.9l-.81-1.2a2 2 0 0 0-1.67-.9H8a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2Z"/><path d="M2 8v11a2 2 0 0 0 2 2h14"/></svg>
        <p>No projects yet. Check back soon!</p>
    </div>
<?php else: ?>
    <nav class="projects-nav">
        <?php foreach ($grouped as $key => $group): ?>
            <?php if (!empty($group['projects'])): ?>
                <a href="#<?= $key ?>" class="projects-nav-item">
                    <span class="projects-nav-label"><?= $group['title'] ?></span>
                    <span class="projects-nav-count"><?= count($group['projects']) ?></span>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>

    <?php foreach ($grouped as $key => $group): ?>
        <?php if (!empty($group['projects'])): ?>
            <section class="projects-section" id="<?= $key ?>">
                <div class="projects-section-header">
                    <div class="projects-section-title">
                        <h2><?= $group['title'] ?></h2>
                        <span class="projects-section-count"><?= count($group['projects']) ?></span>
                    </div>
                    <p class="projects-section-desc"><?= $group['description'] ?></p>
                </div>

                <div class="projects-grid">
                    <?php foreach ($group['projects'] as $project): ?>
                        <a href="/projects/<?= htmlspecialchars($project['slug']) ?>" class="project-card">
                            <?php if ($project['thumbnail']): ?>
                                <img src="<?= htmlspecialchars($project['thumbnail']) ?>" alt="" class="project-card-thumb">
                            <?php else: ?>
                                <div class="project-card-thumb project-card-thumb-empty">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                </div>
                            <?php endif; ?>
                            <div class="project-card-body">
                                <h3><?= htmlspecialchars($project['title']) ?></h3>
                                <span class="status-badge status-<?= $project['status'] ?>">
                                    <?= ucfirst(str_replace('_', ' ', $project['status'])) ?>
                                </span>
                                <?php if ($project['description']): ?>
                                    <p><?= htmlspecialchars(mb_strimwidth($project['description'], 0, 120, '...')) ?></p>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>