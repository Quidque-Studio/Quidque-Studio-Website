<?php use Api\Core\Str; ?>

<div class="member-page">
    <div class="member-profile">
        <div class="editor-page" style="max-width: none;">
            <div class="editor-header">
                <a href="/team/<?= $member['id'] ?>/posts" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Back
                </a>
                <h1 class="editor-title"><?= $post ? 'Edit Post' : 'New Post' ?></h1>
            </div>

            <form method="POST" action="<?= $post ? "/team/{$member['id']}/posts/{$post['id']}" : "/team/{$member['id']}/posts" ?>" class="editor-form">
                <?= \Api\Core\View::csrfField() ?>
                
                <div class="editor-section">
                    <div class="editor-section-header">
                        <div class="editor-section-icon purple">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </div>
                        <span class="editor-section-title">Post Details</span>
                    </div>
                    <div class="editor-section-body">
                        <div class="editor-field">
                            <label class="editor-label">Title</label>
                            <input type="text" name="title" class="editor-input" value="<?= htmlspecialchars($post['title'] ?? '') ?>" placeholder="Post title" required>
                        </div>
                        <div class="editor-field" style="margin-top: 20px;">
                            <label class="editor-label">Tags <span class="optional">(comma separated)</span></label>
                            <input type="text" name="tags" class="editor-input" value="<?= htmlspecialchars(implode(', ', Str::formatTags($post['tags'] ?? null))) ?>" placeholder="personal, update, etc.">
                        </div>
                    </div>
                </div>

                <div class="editor-section">
                    <div class="editor-section-header">
                        <div class="editor-section-icon">
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
                        <a href="/team/<?= $member['id'] ?>/posts" class="btn">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/js/admin/block-editor.js"></script>