<?php
use Api\Core\Date;
?>

<section class="home-hero">
    <div class="hero-content">
        <h1 class="hero-title">Welcome to Quidque</h1>
        <p class="hero-subtitle">
            Building tools, software and digital experiments from the ground up. No shortcuts, just focused development.
        </p>
        <div class="hero-actions">
            <a href="/projects" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20 17a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3.9a2 2 0 0 1-1.69-.9l-.81-1.2a2 2 0 0 0-1.67-.9H8a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2Z"/><path d="M2 8v11a2 2 0 0 0 2 2h14"/></svg>
                Browse Projects
            </a>
            <a href="/about" class="btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Meet the Team
            </a>
        </div>
    </div>
    <div class="hero-visual">
        <img src="/QuidqueLogo.svg" alt="Quidque Logo" class="hero-logo">
    </div>
</section>

<div class="dashboard-grid">
    <div class="dashboard-main">
        <?php if (!empty($featuredProjects)): ?>
        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    Featured Projects
                </h2>
                <a href="/projects" class="section-link">
                    View all
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            </div>
            <div class="featured-grid">
                <?php foreach ($featuredProjects as $project): ?>
                <a href="/projects/<?= htmlspecialchars($project['slug']) ?>" class="project-card">
                    <?php if ($project['thumbnail']): ?>
                        <img src="<?= htmlspecialchars($project['thumbnail']) ?>" alt="" class="project-thumb">
                    <?php else: ?>
                        <div class="project-thumb project-thumb-empty">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                        </div>
                    <?php endif; ?>
                    <div class="project-info">
                        <h3 class="project-name"><?= htmlspecialchars($project['title']) ?></h3>
                        <span class="project-status status-<?= $project['status'] ?>">
                            <?= ucfirst(str_replace('_', ' ', $project['status'])) ?>
                        </span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
    
    <div class="dashboard-sidebar">
        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                    Quick Links
                </h2>
            </div>
            <div class="quick-links">
                <a href="/blog" class="quick-link">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
                    <span>Studio News</span>
                </a>
                <a href="/search" class="quick-link">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <span>Search</span>
                </a>
            </div>
        </section>
        
        <section class="dashboard-section" style="margin-top: 24px;">
            <div class="section-header">
                <h2 class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/></svg>
                    Recent Activity
                </h2>
            </div>
            <div class="card">
                <div class="card-body" style="padding: 6px 16px;">
                    <div class="activity-list">
                        <?php
                        $allActivity = [];
                        
                        foreach ($recentPosts as $post) {
                            $allActivity[] = [
                                'type' => 'news',
                                'title' => $post['title'],
                                'url' => '/blog/' . $post['slug'],
                                'meta' => 'Studio News',
                                'date' => $post['created_at'],
                            ];
                        }
                        
                        foreach ($recentDevlogs as $devlog) {
                            $allActivity[] = [
                                'type' => 'devlog',
                                'title' => $devlog['title'],
                                'url' => '/projects/' . $devlog['project_slug'] . '/devlogs/' . $devlog['slug'],
                                'meta' => $devlog['project_title'],
                                'date' => $devlog['created_at'],
                            ];
                        }
                        
                        foreach ($recentMemberPosts as $memberPost) {
                            $allActivity[] = [
                                'type' => 'member',
                                'title' => $memberPost['title'],
                                'url' => '/team/' . $memberPost['author_id'] . '/posts/' . $memberPost['slug'],
                                'meta' => $memberPost['author_name'] . "'s Blog",
                                'date' => $memberPost['created_at'],
                            ];
                        }
                        
                        usort($allActivity, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
                        $allActivity = array_slice($allActivity, 0, 8);
                        ?>
                        
                        <?php if (empty($allActivity)): ?>
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            <p>No recent activity yet</p>
                        </div>
                        <?php else: ?>
                            <?php foreach ($allActivity as $activity): ?>
                            <a href="<?= htmlspecialchars($activity['url']) ?>" class="activity-item">
                                <div class="activity-icon <?= $activity['type'] === 'devlog' ? 'devlog' : ($activity['type'] === 'member' ? 'member' : '') ?>">
                                    <?php if ($activity['type'] === 'news'): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
                                    <?php elseif ($activity['type'] === 'devlog'): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                                    <?php else: ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    <?php endif; ?>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title"><?= htmlspecialchars($activity['title']) ?></div>
                                    <div class="activity-meta">
                                        <span><?= htmlspecialchars($activity['meta']) ?></span>
                                        <span><?= Date::relative($activity['date']) ?></span>
                                    </div>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>