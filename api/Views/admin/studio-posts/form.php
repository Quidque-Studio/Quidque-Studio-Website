<?php use Api\Core\Str; ?>

<div class="editor-page">
    <div class="editor-header">
        <a href="/admin/studio-posts" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Back
        </a>
        <h1 class="editor-title"><?= $post ? 'Edit Post' : 'New Post' ?></h1>
    </div>

    <form method="POST" action="<?= $post ? "/admin/studio-posts/{$post['id']}" : '/admin/studio-posts' ?>" class="editor-form">
        <?= \Api\Core\View::csrfField() ?>
        
        <div class="editor-section">
            <div class="editor-section-header">
                <div class="editor-section-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
                </div>
                <span class="editor-section-title">Post Details</span>
            </div>
            <div class="editor-section-body">
                <div class="editor-row">
                    <div class="editor-field">
                        <label class="editor-label">Title</label>
                        <input type="text" name="title" class="editor-input" value="<?= htmlspecialchars($post['title'] ?? '') ?>" placeholder="Post title" required>
                    </div>
                    <div class="editor-field">
                        <label class="editor-label">Category <span class="optional">(optional)</span></label>
                        <select name="category_id" class="editor-select">
                            <option value="">None</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($post['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="editor-field" style="margin-top: 20px;">
                    <label class="editor-label">Tags <span class="optional">(comma separated)</span></label>
                    <input type="text" name="tags" class="editor-input" value="<?= htmlspecialchars(implode(', ', Str::formatTags($post['tags'] ?? null))) ?>" placeholder="announcement, update, etc.">
                </div>
            </div>
        </div>

        <div class="editor-section">
            <div class="editor-section-header">
                <div class="editor-section-icon purple">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l7.586 7.586"/><circle cx="11" cy="11" r="2"/></svg>
                </div>
                <span class="editor-section-title">Content</span>
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
                <input type="hidden" name="content" id="content-json" value="<?= htmlspecialchars($post['content'] ?? '[]') ?>">
            </div>
        </div>

        <div class="editor-section">
            <div class="editor-actions">
                <button type="submit" class="btn btn-primary"><?= $post ? 'Update Post' : 'Create Post' ?></button>
                <a href="/admin/studio-posts" class="btn">Cancel</a>
            </div>
        </div>
    </form>
</div>

<script src="/js/admin/block-editor.js"></script>