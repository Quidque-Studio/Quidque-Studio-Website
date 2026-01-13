<?php
use Api\Core\ContentRenderer;

$defaultPalette = [
    'bg' => '#0a1214',
    'bgSurface' => '#0f1a1d',
    'panel' => '#142125',
    'panelHover' => '#1a2a2f',
    'primary' => '#00ffbb',
    'primaryDim' => 'rgba(0, 255, 187, 0.15)',
    'primaryGlow' => 'rgba(0, 255, 187, 0.3)',
    'accent' => '#ff00ff',
    'accentDim' => 'rgba(255, 0, 255, 0.15)',
    'accentGlow' => 'rgba(255, 0, 255, 0.3)',
    'purple' => '#9d7edb',
    'purpleDim' => 'rgba(157, 126, 219, 0.15)',
    'text' => '#f0f4f5',
    'textSecondary' => '#8a9da3',
    'textMuted' => '#5a6d73',
    'border' => 'rgba(0, 255, 187, 0.12)',
    'borderSubtle' => 'rgba(255, 255, 255, 0.06)',
];

$customPalette = json_decode($member['color_palette'] ?? '{}', true) ?: [];
$palette = array_merge($defaultPalette, $customPalette);

$socialLinks = json_decode($member['social_links'] ?? '[]', true) ?: [];

$cssVarMap = [
    'bg' => '--bg-color',
    'bgSurface' => '--bg-surface',
    'panel' => '--panel-bg',
    'panelHover' => '--panel-hover',
    'primary' => '--primary',
    'primaryDim' => '--primary-dim',
    'primaryGlow' => '--primary-glow',
    'accent' => '--accent',
    'accentDim' => '--accent-dim',
    'accentGlow' => '--accent-glow',
    'purple' => '--purple',
    'purpleDim' => '--purple-dim',
    'text' => '--text-primary',
    'textSecondary' => '--text-secondary',
    'textMuted' => '--text-muted',
    'border' => '--border-color',
    'borderSubtle' => '--border-subtle',
];
?>

<?php if (!empty($customPalette)): ?>
<style>
:root {
<?php foreach ($customPalette as $key => $value): ?>
<?php if (isset($cssVarMap[$key])): ?>
    <?= $cssVarMap[$key] ?>: <?= htmlspecialchars($value) ?>;
<?php endif; ?>
<?php endforeach; ?>
}
</style>
<?php endif; ?>

<div class="member-page">
    <div class="member-profile">
        <div class="member-header">
            <div class="member-avatar">
                <?php if ($member['avatar']): ?>
                    <img src="<?= htmlspecialchars($member['avatar']) ?>" alt="">
                <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <?php endif; ?>
            </div>
            <div class="member-info">
                <h1 class="member-name"><?= htmlspecialchars($member['name']) ?></h1>
                <?php if ($member['role_title']): ?>
                    <p class="member-role"><?= htmlspecialchars($member['role_title']) ?></p>
                <?php endif; ?>
                <?php if ($member['short_bio']): ?>
                    <p class="member-bio"><?= htmlspecialchars($member['short_bio']) ?></p>
                <?php endif; ?>
                <?php if (!empty($socialLinks)): ?>
                    <div class="member-socials">
                        <?php foreach ($socialLinks as $link): ?>
                            <a href="<?= htmlspecialchars($link['url']) ?>" target="_blank"><?= htmlspecialchars($link['platform']) ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="member-nav">
            <a href="/team/<?= $member['id'] ?>" class="active">About</a>
            <a href="/team/<?= $member['id'] ?>/posts">Blog</a>
        </div>

        <?php if ($canEdit): ?>
            <div class="editor-page" style="max-width: none;">
                <form method="POST" action="/team/<?= $member['id'] ?>/about" class="editor-form">
                    <?= \Api\Core\View::csrfField() ?>
                    
                    <div class="editor-section">
                        <div class="editor-section-header">
                            <div class="editor-section-icon purple">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <span class="editor-section-title">About Me</span>
                        </div>
                        <div class="editor-section-body" style="padding: 0;">
                            <div class="block-editor">
                                <div class="block-editor-header">
                                    <span class="block-editor-title">Content Blocks</span>
                                    <span class="block-editor-count" id="block-count">0 blocks</span>
                                </div>
                                <div class="blocks-container" id="blocks-container"></div>
                                <div class="block-add-bar">
                                    <select id="block-type" class="block-add-select">
                                        <option value="text">Text</option>
                                        <option value="heading">Heading</option>
                                        <option value="image">Image</option>
                                        <option value="code">Code</option>
                                        <option value="quote">Quote</option>
                                        <option value="list">List</option>
                                        <option value="callout">Callout</option>
                                        <option value="video">Video</option>
                                        <option value="divider">Divider</option>
                                    </select>
                                    <button type="button" id="add-block" class="btn btn-primary">Add Block</button>
                                </div>
                            </div>
                            <input type="hidden" name="about_content" id="content-json" value="<?= htmlspecialchars($member['about_content'] ?? '[]') ?>">
                        </div>
                    </div>

                    <div class="editor-section">
                        <div class="editor-actions">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
            <script src="/js/admin/block-editor.js"></script>
        <?php else: ?>
            <div class="member-content">
                <?php if (empty($member['about_content']) || $member['about_content'] === '[]'): ?>
                    <div class="member-empty">
                        <p>This member hasn't added any content yet.</p>
                    </div>
                <?php else: ?>
                    <?= ContentRenderer::render($member['about_content']) ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>