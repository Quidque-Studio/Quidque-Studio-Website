<?php
$paletteLabels = [
    'bg' => 'Background',
    'panel' => 'Panel Background',
    'accent' => 'Accent (links, buttons)',
    'highlight' => 'Highlight (hover, active)',
    'success' => 'Success/Positive',
    'text' => 'Text',
    'textMuted' => 'Muted Text',
    'border' => 'Borders',
];

$currentPalette = [];
if (!empty($profile['color_palette'])) {
    $currentPalette = json_decode($profile['color_palette'], true) ?? [];
}
?>

<div class="settings-container">
    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= $flash['type'] ?>"><?= htmlspecialchars($flash['message']) ?></div>
    <?php endif; ?>

    <section class="settings-section">
        <h2>Account</h2>
        <form method="POST" action="/settings">
            <?= \Api\Core\View::csrfField() ?>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
            </div>
            <div class="form-group">
                <label for="name">Display Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </section>

    <section class="settings-section">
        <h2>Avatar</h2>
        <form method="POST" action="/settings/avatar" enctype="multipart/form-data">
            <?= \Api\Core\View::csrfField() ?>
            <div class="avatar-preview">
                <?php if ($user['avatar']): ?>
                    <img src="<?= $user['avatar'] ?>" alt="Avatar">
                <?php else: ?>
                    <div class="avatar-placeholder">No avatar</div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp">
                <small>Will be cropped to square and resized to 128x128</small>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </section>

    <section class="settings-section">
        <h2>Newsletter</h2>
        <form method="POST" action="/settings/newsletter">
            <?= \Api\Core\View::csrfField() ?>
            <?php if ($isSubscribed): ?>
                <p>You are currently <strong>subscribed</strong> to the Quidque Studio newsletter.</p>
                <button type="submit" class="btn">Unsubscribe</button>
            <?php else: ?>
                <p>Stay updated with our latest projects, devlogs, and announcements.</p>
                <input type="hidden" name="subscribe" value="1">
                <button type="submit" class="btn btn-primary">Subscribe</button>
            <?php endif; ?>
        </form>
    </section>

    <?php if ($user['role'] === 'team_member'): ?>
    <section class="settings-section">
        <h2>Team Profile</h2>
        <form method="POST" action="/settings/profile">
            <?= \Api\Core\View::csrfField() ?>
            <div class="form-group">
                <label for="role_title">Role Title</label>
                <input type="text" id="role_title" name="role_title" value="<?= htmlspecialchars($profile['role_title'] ?? '') ?>" placeholder="e.g. Lead Developer">
            </div>
            <div class="form-group">
                <label for="short_bio">Short Bio / Tagline</label>
                <input type="text" id="short_bio" name="short_bio" value="<?= htmlspecialchars($profile['short_bio'] ?? '') ?>" placeholder="A short funny line or description">
            </div>

            <div class="form-group">
                <label>Social Links</label>
                <div id="social-links">
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
                <button type="button" id="add-social" class="btn">Add Link</button>
            </div>

            <div class="form-group">
                <label>Color Palette</label>
                <p class="form-hint">Customize your page colors. Leave blank to use defaults.</p>
                <div class="palette-grid">
                    <?php foreach ($defaultPalette as $key => $default): ?>
                        <div class="palette-item">
                            <label for="palette_<?= $key ?>"><?= $paletteLabels[$key] ?? $key ?></label>
                            <div class="palette-input-row">
                                <input type="color" 
                                       id="palette_<?= $key ?>" 
                                       name="palette[<?= $key ?>]" 
                                       value="<?= htmlspecialchars($currentPalette[$key] ?? $default) ?>"
                                       data-default="<?= htmlspecialchars($default) ?>">
                                <button type="button" class="palette-reset btn" data-target="palette_<?= $key ?>">Reset</button>
                            </div>
                            <small class="palette-default">Default: <?= htmlspecialchars($default) ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Profile</button>
        </form>
        <p class="settings-note">Edit your About page content on your <a href="/team/<?= $user['id'] ?>">public profile page</a>.</p>
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