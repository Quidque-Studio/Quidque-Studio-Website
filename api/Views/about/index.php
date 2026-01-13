<div class="about-hero">
    <div class="about-hero-logo">
        <img src="/QuidqueLogo.png" alt="Quidque Logo">
    </div>
    <div class="about-hero-content">
        <h1>About Quidque Studio</h1>
        <p>
            "Quidque" is Latin for "each" or "any", a name chosen to reflect a passion for building whatever comes to mind. Anything from low level systems to indie games. <br><br>
            Our focus is on craftmanship. We build projects step by step, ensuring the final product is something we can be proud of.
            The goal isn't to flood the web with projects, but to create stable, usable solutions that are built to last.
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
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
            Our Values
        </h2>
    </div>
    <div class="values-grid">
        <div class="value-card">
            <div class="value-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="16" x="4" y="4" rx="2"/><rect width="6" height="6" x="9" y="9" rx="1"/><path d="M9 1h1"/><path d="M14 1h1"/><path d="M23 9v1"/><path d="M23 14v1"/><path d="M15 23h-1"/><path d="M10 23H9"/><path d="M1 15v-1"/><path d="M1 10V9"/></svg>
            </div>
            <h3>From The Ground Up</h3>
            <p>We believe in understanding how things work by avoiding excessive dependencies and keeping code clean and intentional.</p>
        </div>

        <div class="value-card">
            <div class="value-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 9.4 7.5 4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" x2="12" y1="22" y2="12"/></svg>
            </div>
            <h3>Usability First</h3>
            <p>We value finished products that anyone can use without issues. If a project is on this site, it has earned its place here.</p>
        </div>

        <div class="value-card">
            <div class="value-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <h3>Open Progress</h3>
            <p>Most of what we build is open-source. We share the journey through honest devlogs and blogs, documenting the ups, the downs, and the process behind the code.</p>
        </div>
    </div>
</section>