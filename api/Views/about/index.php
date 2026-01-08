<div class="about-hero">
    <div class="about-hero-logo">
        <img src="/QuidqueLogo.png" alt="Quidque Logo">
    </div>
    <div class="about-hero-content">
        <h1>About Quidque Studio</h1>
        <p>
            We're a small indie studio exploring game development, creative tools, and everything in between. 
            "Quidque" is Latin for "each" or "any" — reflecting our philosophy of trying a little bit of everything 
            rather than specializing in one area. Follow our journey through projects, devlogs, and experiments.
        </p>
    </div>
</div>

<?php if (!empty($teamMembers)): ?>
<section class="about-section">
    <div class="about-section-header">
        <h2 class="about-section-title">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            The Team
        </h2>
    </div>
    <div class="team-grid">
        <?php foreach ($teamMembers as $member): ?>
        <a href="/team/<?= $member['id'] ?>" class="team-card">
            <div class="team-avatar">
                <?php if ($member['avatar']): ?>
                    <img src="<?= htmlspecialchars($member['avatar']) ?>" alt="">
                <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <?php endif; ?>
            </div>
            <div class="team-info">
                <div class="team-name"><?= htmlspecialchars($member['name']) ?></div>
                <?php if ($member['role_title']): ?>
                    <div class="team-role"><?= htmlspecialchars($member['role_title']) ?></div>
                <?php endif; ?>
                <?php if ($member['short_bio']): ?>
                    <div class="team-bio"><?= htmlspecialchars($member['short_bio']) ?></div>
                <?php endif; ?>
            </div>
            <div class="team-arrow">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section class="about-section">
    <div class="about-section-header">
        <h2 class="about-section-title">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
            Our Values
        </h2>
    </div>
    <div class="values-grid">
        <div class="value-card">
            <div class="value-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            </div>
            <h3>Always Learning</h3>
            <p>Every project is an opportunity to explore new technologies, techniques, and ideas.</p>
        </div>
        <div class="value-card">
            <div class="value-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <h3>Open Development</h3>
            <p>We share our progress, challenges, and lessons learned through devlogs and updates.</p>
        </div>
        <div class="value-card">
            <div class="value-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
            <h3>Quality Over Quantity</h3>
            <p>We'd rather make one thing well than rush out many half-finished projects.</p>
        </div>
    </div>
</section>