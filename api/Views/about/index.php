<div class="about-page">
    <header class="page-header">
        <h1>About Quidque Studio</h1>
    </header>

    <section class="about-intro">
        <p>Games, Tools, and Devlogs.</p>
        <!-- Add more hardcoded intro text here -->
    </section>

    <?php if (!empty($teamMembers)): ?>
        <section class="team-section">
            <h2>The Team</h2>
            <div class="team-grid">
                <?php foreach ($teamMembers as $member): ?>
                    <a href="/team/<?= $member['id'] ?>" class="team-card" <?php if ($member['accent_color']): ?>style="--accent: <?= htmlspecialchars($member['accent_color']) ?>"<?php endif; ?>>
                        <?php if ($member['avatar']): ?>
                            <img src="<?= htmlspecialchars($member['avatar']) ?>" alt="" class="team-avatar">
                        <?php else: ?>
                            <div class="team-avatar team-avatar-empty"></div>
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($member['name']) ?></h3>
                        <?php if ($member['role_title']): ?>
                            <span class="team-role"><?= htmlspecialchars($member['role_title']) ?></span>
                        <?php endif; ?>
                        <?php if ($member['short_bio']): ?>
                            <p><?= htmlspecialchars($member['short_bio']) ?></p>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>