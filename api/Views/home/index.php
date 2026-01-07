<?php use Api\Core\ContentRenderer; ?>

<div class="home-page">
    <section class="hero">
        <h1>Quidque Studio</h1>
        <p>Games, Tools, and Devlogs</p>
    </section>

    <?php if (!empty($featuredProjects)): ?>
        <section class="home-section">
            <h2>Featured Projects</h2>
            <div class="featured-projects">
                <?php foreach ($featuredProjects as $project): ?>
                    <a href="/projects/<?= htmlspecialchars($project['slug']) ?>" class="project-card">
                        <?php if ($project['thumbnail']): ?>
                            <img src="<?= htmlspecialchars($project['thumbnail']) ?>" alt="" class="project-card-thumb">
                        <?php endif; ?>
                        <div class="project-card-body">
                            <h3><?= htmlspecialchars($project['title']) ?></h3>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            <a href="/projects" class="view-all">View all projects →</a>
        </section>
    <?php endif; ?>

    <?php if (!empty($recentPosts)): ?>
        <section class="home-section">
            <h2>Latest News</h2>
            <ul class="recent-list">
                <?php foreach ($recentPosts as $post): ?>
                    <li>
                        <a href="/blog/<?= htmlspecialchars($post['slug']) ?>">
                            <span class="date"><?= date('M j', strtotime($post['created_at'])) ?></span>
                            <span><?= htmlspecialchars($post['title']) ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <a href="/blog" class="view-all">View all posts →</a>
        </section>
    <?php endif; ?>

    <?php if (!empty($recentDevlogs)): ?>
        <section class="home-section">
            <h2>Recent Devlogs</h2>
            <ul class="recent-list">
                <?php foreach ($recentDevlogs as $devlog): ?>
                    <li>
                        <a href="/projects/<?= htmlspecialchars($devlog['project_slug']) ?>/devlogs/<?= htmlspecialchars($devlog['slug']) ?>">
                            <span class="date"><?= date('M j', strtotime($devlog['created_at'])) ?></span>
                            <span><?= htmlspecialchars($devlog['title']) ?></span>
                            <small><?= htmlspecialchars($devlog['project_title']) ?></small>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>
</div>