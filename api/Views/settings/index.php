<div class="settings-container">
    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= $flash['type'] ?>"><?= htmlspecialchars($flash['message']) ?></div>
    <?php endif; ?>

    <section class="settings-section">
        <h2>Account</h2>
        <form method="POST" action="/settings">
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

    <?php if ($user['role'] === 'team_member'): ?>
    <section class="settings-section">
        <h2>Team Profile</h2>
        <form method="POST" action="/settings/profile">
            <div class="form-group">
                <label for="role_title">Role Title</label>
                <input type="text" id="role_title" name="role_title" value="<?= htmlspecialchars($profile['role_title'] ?? '') ?>" placeholder="e.g. Lead Developer">
            </div>
            <div class="form-group">
                <label for="short_bio">Short Bio / Tagline</label>
                <input type="text" id="short_bio" name="short_bio" value="<?= htmlspecialchars($profile['short_bio'] ?? '') ?>" placeholder="A short funny line or description">
            </div>
            <div class="form-group">
                <label for="accent_color">Accent Color</label>
                <input type="color" id="accent_color" name="accent_color" value="<?= htmlspecialchars($profile['accent_color'] ?? '#9d7edb') ?>">
            </div>
            <div class="form-group">
                <label for="bg_color">Background Color</label>
                <input type="color" id="bg_color" name="bg_color" value="<?= htmlspecialchars($profile['bg_color'] ?? '#012a31') ?>">
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
</script>