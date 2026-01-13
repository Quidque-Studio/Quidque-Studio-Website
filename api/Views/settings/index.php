<?php
$paletteLabels = [
    'bg' => 'Background',
    'bgSurface' => 'Surface',
    'panel' => 'Panel',
    'panelHover' => 'Panel Hover',
    'primary' => 'Primary',
    'primaryDim' => 'Primary Dim',
    'primaryGlow' => 'Primary Glow',
    'accent' => 'Accent',
    'accentDim' => 'Accent Dim',
    'accentGlow' => 'Accent Glow',
    'purple' => 'Purple',
    'purpleDim' => 'Purple Dim',
    'text' => 'Text',
    'textSecondary' => 'Text Secondary',
    'textMuted' => 'Text Muted',
    'border' => 'Border',
    'borderSubtle' => 'Border Subtle',
];

$paletteGroups = [
    'Backgrounds' => ['bg', 'bgSurface', 'panel', 'panelHover'],
    'Primary' => ['primary', 'primaryDim', 'primaryGlow'],
    'Accent' => ['accent', 'accentDim', 'accentGlow'],
    'Purple' => ['purple', 'purpleDim'],
    'Text' => ['text', 'textSecondary', 'textMuted'],
    'Borders' => ['border', 'borderSubtle'],
];

$currentPalette = [];
if (!empty($profile['color_palette'])) {
    $currentPalette = json_decode($profile['color_palette'], true) ?? [];
}

function isHexColor(string $value): bool {
    return preg_match('/^#[0-9A-Fa-f]{6}$/', $value) || preg_match('/^#[0-9A-Fa-f]{3}$/', $value);
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
                    <div class="palette-actions" style="margin-top: 12px; margin-bottom: 16px;">
                        <button type="button" id="preview-palette" class="btn">Preview Theme</button>
                        <button type="button" id="reset-all-palette" class="btn">Reset All</button>
                        <button type="button" id="close-preview" class="btn" style="display: none;">Close Preview</button>
                    </div>
                    
                    <?php foreach ($paletteGroups as $groupName => $keys): ?>
                    <div class="palette-group">
                        <div class="palette-group-title"><?= $groupName ?></div>
                        <div class="palette-grid">
                            <?php foreach ($keys as $key): 
                                $currentValue = $currentPalette[$key] ?? $defaultPalette[$key];
                                $defaultValue = $defaultPalette[$key];
                                $isHex = isHexColor($currentValue);
                                $colorPickerValue = $isHex ? $currentValue : '#888888';
                            ?>
                                <div class="palette-item">
                                    <label for="palette_<?= $key ?>"><?= $paletteLabels[$key] ?? $key ?></label>
                                    <div class="palette-input-row">
                                        <input type="color" 
                                               id="palette_color_<?= $key ?>" 
                                               value="<?= htmlspecialchars($colorPickerValue) ?>"
                                               class="palette-color-input"
                                               data-target="palette_<?= $key ?>">
                                        <input type="text" 
                                               id="palette_<?= $key ?>" 
                                               name="palette[<?= $key ?>]" 
                                               value="<?= htmlspecialchars($currentValue) ?>"
                                               data-default="<?= htmlspecialchars($defaultValue) ?>"
                                               class="palette-text-input"
                                               placeholder="<?= htmlspecialchars($defaultValue) ?>">
                                        <button type="button" class="palette-reset" data-target="palette_<?= $key ?>" data-color-target="palette_color_<?= $key ?>">↺</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
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

<div id="palette-preview-panel" class="palette-preview-panel">
    <div class="palette-preview-header">
        <span>Theme Preview</span>
        <button type="button" id="close-preview-panel">×</button>
    </div>
    <div class="palette-preview-content">
        <div class="preview-section">
            <div class="preview-card">
                <div class="preview-card-header">Sample Card</div>
                <div class="preview-card-body">
                    <p class="preview-text-primary">Primary text content</p>
                    <p class="preview-text-secondary">Secondary text content</p>
                    <p class="preview-text-muted">Muted text content</p>
                </div>
            </div>
        </div>
        <div class="preview-section">
            <div class="preview-buttons">
                <button class="preview-btn-primary">Primary Button</button>
                <button class="preview-btn-accent">Accent Button</button>
                <button class="preview-btn-default">Default Button</button>
            </div>
        </div>
        <div class="preview-section">
            <div class="preview-badges">
                <span class="preview-badge-primary">Primary</span>
                <span class="preview-badge-accent">Accent</span>
                <span class="preview-badge-purple">Purple</span>
            </div>
        </div>
        <div class="preview-section">
            <div class="preview-avatar"></div>
            <div class="preview-member-info">
                <div class="preview-member-name">Member Name</div>
                <div class="preview-member-role">Role Title</div>
            </div>
        </div>
    </div>
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

document.querySelectorAll('.palette-color-input').forEach(colorInput => {
    colorInput.addEventListener('input', function() {
        const textInput = document.getElementById(this.dataset.target);
        if (textInput) {
            textInput.value = this.value;
            updatePreview();
        }
    });
});

document.querySelectorAll('.palette-text-input').forEach(textInput => {
    textInput.addEventListener('input', function() {
        const key = this.id.replace('palette_', '');
        const colorInput = document.getElementById('palette_color_' + key);
        if (colorInput && /^#[0-9A-Fa-f]{6}$/.test(this.value)) {
            colorInput.value = this.value;
        }
        updatePreview();
    });
});

document.querySelectorAll('.palette-reset').forEach(btn => {
    btn.addEventListener('click', function() {
        const textInput = document.getElementById(this.dataset.target);
        const colorInput = document.getElementById(this.dataset.colorTarget);
        if (textInput) {
            textInput.value = textInput.dataset.default;
            if (colorInput && /^#[0-9A-Fa-f]{6}$/.test(textInput.dataset.default)) {
                colorInput.value = textInput.dataset.default;
            }
            updatePreview();
        }
    });
});

document.getElementById('reset-all-palette')?.addEventListener('click', function() {
    document.querySelectorAll('.palette-text-input').forEach(textInput => {
        textInput.value = textInput.dataset.default;
        const key = textInput.id.replace('palette_', '');
        const colorInput = document.getElementById('palette_color_' + key);
        if (colorInput && /^#[0-9A-Fa-f]{6}$/.test(textInput.dataset.default)) {
            colorInput.value = textInput.dataset.default;
        }
    });
    updatePreview();
});

const previewPanel = document.getElementById('palette-preview-panel');
const previewBtn = document.getElementById('preview-palette');
const closePreviewBtn = document.getElementById('close-preview');
const closePreviewPanelBtn = document.getElementById('close-preview-panel');

function getCSSVarName(key) {
    const varMap = {
        'bg': '--bg-color',
        'bgSurface': '--bg-surface',
        'panel': '--panel-bg',
        'panelHover': '--panel-hover',
        'primary': '--primary',
        'primaryDim': '--primary-dim',
        'primaryGlow': '--primary-glow',
        'accent': '--accent',
        'accentDim': '--accent-dim',
        'accentGlow': '--accent-glow',
        'purple': '--purple',
        'purpleDim': '--purple-dim',
        'text': '--text-primary',
        'textSecondary': '--text-secondary',
        'textMuted': '--text-muted',
        'border': '--border-color',
        'borderSubtle': '--border-subtle'
    };
    return varMap[key] || key;
}

function updatePreview() {
    if (!previewPanel) return;
    
    document.querySelectorAll('.palette-text-input').forEach(input => {
        const key = input.id.replace('palette_', '');
        const cssVar = getCSSVarName(key);
        const value = input.value || input.dataset.default;
        previewPanel.style.setProperty(cssVar, value);
    });
}

function showPreview() {
    previewPanel.classList.add('active');
    closePreviewBtn.style.display = 'inline-flex';
    previewBtn.style.display = 'none';
    updatePreview();
}

function hidePreview() {
    previewPanel.classList.remove('active');
    closePreviewBtn.style.display = 'none';
    previewBtn.style.display = 'inline-flex';
}

previewBtn?.addEventListener('click', showPreview);
closePreviewBtn?.addEventListener('click', hidePreview);
closePreviewPanelBtn?.addEventListener('click', hidePreview);
</script>