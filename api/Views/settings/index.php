<?php
$paletteLabels = [
    'bg' => 'Background',
    'panel' => 'Panel',
    'accent' => 'Accent',
    'highlight' => 'Highlight',
    'success' => 'Success',
    'text' => 'Text',
    'textMuted' => 'Muted',
    'border' => 'Border',
];

$currentPalette = [];
if (!empty($profile['color_palette'])) {
    $currentPalette = json_decode($profile['color_palette'], true) ?? [];
}
?>

<div class="settings-page">
    <div class="settings-header">
        <h1 class="settings-title">Settings</h1>
        <p class="settings-subtitle">Manage your account and preferences</p>
    </div>

    <section class="settings-section">
        <div class="settings-section-header">
            <div class="settings-section-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <h2 class="settings-section-title">Account</h2>
        </div>
        <div class="settings-section-body">
            <form method="POST" action="/settings" class="settings-form">
                <?= \Api\Core\View::csrfField() ?>
                <div class="settings-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                </div>
                <div class="settings-field">
                    <label for="name">Display Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>
                <div class="settings-actions">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </section>

    <section class="settings-section">
        <div class="settings-section-header">
            <div class="settings-section-icon purple">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
            </div>
            <h2 class="settings-section-title">Avatar</h2>
        </div>
        <div class="settings-section-body">
            <form method="POST" action="/settings/avatar" enctype="multipart/form-data" class="settings-form">
                <?= \Api\Core\View::csrfField() ?>
                <div class="avatar-section">
                    <div class="avatar-preview">
                        <?php if ($user['avatar']): ?>
                            <img src="<?= $user['avatar'] ?>" alt="Avatar">
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <?php endif; ?>
                    </div>
                    <div class="avatar-upload">
                        <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp">
                        <small>JPG, PNG or WebP. Will be cropped to 128×128.</small>
                    </div>
                </div>
                <div class="settings-actions">
                    <button type="submit" class="btn btn-primary">Upload Avatar</button>
                </div>
            </form>
        </div>
    </section>

    <section class="settings-section">
        <div class="settings-section-header">
            <div class="settings-section-icon accent">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
            <h2 class="settings-section-title">Newsletter</h2>
        </div>
        <div class="settings-section-body">
            <form method="POST" action="/settings/newsletter">
                <?= \Api\Core\View::csrfField() ?>
                <div class="newsletter-status">
                    <div class="newsletter-info">
                        <?php if ($isSubscribed): ?>
                            <p>You're subscribed to studio updates.</p>
                            <span class="newsletter-badge subscribed">Subscribed</span>
                        <?php else: ?>
                            <p>Get notified about new projects and updates.</p>
                        <?php endif; ?>
                    </div>
                    <?php if ($isSubscribed): ?>
                        <button type="submit" class="btn">Unsubscribe</button>
                    <?php else: ?>
                        <input type="hidden" name="subscribe" value="1">
                        <button type="submit" class="btn btn-primary">Subscribe</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </section>

    <?php if ($user['role'] === 'team_member'): ?>
    <section class="settings-section">
        <div class="settings-section-header">
            <div class="settings-section-icon purple">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <h2 class="settings-section-title">Team Profile</h2>
        </div>
        <div class="settings-section-body">
            <form method="POST" action="/settings/profile" class="settings-form">
                <?= \Api\Core\View::csrfField() ?>
                <div class="settings-field">
                    <label for="role_title">Role Title</label>
                    <input type="text" id="role_title" name="role_title" value="<?= htmlspecialchars($profile['role_title'] ?? '') ?>" placeholder="e.g. Lead Developer">
                </div>
                <div class="settings-field">
                    <label for="short_bio">Short Bio</label>
                    <input type="text" id="short_bio" name="short_bio" value="<?= htmlspecialchars($profile['short_bio'] ?? '') ?>" placeholder="A brief tagline or description">
                </div>

                <div class="settings-field">
                    <label>Social Links</label>
                    <div id="social-links" class="social-links-list">
                        <?php 
                        $links = json_decode($profile['social_links'] ?? '[]', true);
                        if (empty($links)) $links = [['platform' => '', 'url' => '']];
                        foreach ($links as $i => $link): 
                        ?>
                            <div class="social-link-row">
                                <input type="text" name="social_platform[]" value="<?= htmlspecialchars($link['platform']) ?>" placeholder="Platform">
                                <input type="url" name="social_url[]" value="<?= htmlspecialchars($link['url']) ?>" placeholder="URL">
                                <button type="button" class="remove-social">×</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="add-social" class="btn" style="margin-top: 12px;">Add Link</button>
                </div>

                <div class="settings-field">
                    <label>Color Palette</label>
                    <small>Customize your public profile page colors</small>
                    <div class="palette-grid" style="margin-top: 12px;">
                        <?php foreach ($defaultPalette as $key => $default): ?>
                            <div class="palette-item">
                                <label for="palette_<?= $key ?>"><?= $paletteLabels[$key] ?? $key ?></label>
                                <div class="palette-input-row">
                                    <input type="color" 
                                           id="palette_<?= $key ?>" 
                                           name="palette[<?= $key ?>]" 
                                           value="<?= htmlspecialchars($currentPalette[$key] ?? $default) ?>"
                                           data-default="<?= htmlspecialchars($default) ?>">
                                    <button type="button" class="palette-reset" data-target="palette_<?= $key ?>">Reset</button>
                                </div>
                                <span class="palette-default"><?= htmlspecialchars($default) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="settings-actions">
                    <button type="submit" class="btn btn-primary">Save Profile</button>
                </div>
            </form>
            <p class="settings-note">Edit your About page content on your <a href="/team/<?= $user['id'] ?>">public profile</a>.</p>
        </div>
    </section>
    <?php endif; ?>
</div>

<script>
document.getElementById('add-social')?.addEventListener('click', function() {
    const container = document.getElementById('social-links');
    const row = document.createElement('div');
    row.className = 'social-link-row';
    row.innerHTML = `
        <input type="text" name="social_platform[]" placeholder="Platform">
        <input type="url" name="social_url[]" placeholder="URL">
        <button type="button" class="remove-social">×</button>
    `;
    container.appendChild(row);
});

document.getElementById('social-links')?.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-social')) {
        e.target.closest('.social-link-row').remove();
    }
});

document.querySelectorAll('.palette-reset').forEach(btn => {
    btn.addEventListener('click', function() {
        const target = document.getElementById(this.dataset.target);
        if (target) {
            target.value = target.dataset.default;
        }
    });
});
</script>