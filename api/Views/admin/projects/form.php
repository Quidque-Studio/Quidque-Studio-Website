<?php use Api\Core\Str; ?>

<div class="editor-page">
    <div class="editor-header">
        <a href="/admin/projects" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            <span>Back</span>
        </a>
        <h1 class="editor-title"><?= $project ? 'Edit Project' : 'New Project' ?></h1>
    </div>

    <form method="POST" action="<?= $project ? "/admin/projects/{$project['id']}" : '/admin/projects' ?>" enctype="multipart/form-data" class="editor-form">
        <?= \Api\Core\View::csrfField() ?>
        
        <div class="editor-section">
            <div class="editor-section-header">
                <div class="editor-section-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <span class="editor-section-title">Basic Information</span>
            </div>
            <div class="editor-section-body">
                <div class="editor-row">
                    <div class="editor-field">
                        <label class="editor-label">Title</label>
                        <input type="text" name="title" class="editor-input" value="<?= htmlspecialchars($project['title'] ?? '') ?>" placeholder="Project name" required>
                    </div>
                    <div class="editor-field">
                        <label class="editor-label">Status</label>
                        <select name="status" class="editor-select">
                            <?php foreach (['planned', 'in_progress', 'live_service', 'completed', 'on_hold', 'abandoned'] as $status): ?>
                                <option value="<?= $status ?>" <?= ($project['status'] ?? '') === $status ? 'selected' : '' ?>>
                                    <?= ucfirst(str_replace('_', ' ', $status)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="editor-field full-width">
                    <label class="editor-label">Description <span class="optional">(optional)</span></label>
                    <textarea name="description" class="editor-textarea" placeholder="Brief description of the project"><?= htmlspecialchars($project['description'] ?? '') ?></textarea>
                </div>
                <div class="editor-field">
                    <label class="editor-toggle">
                        <input type="checkbox" name="is_featured" <?= ($project['is_featured'] ?? false) ? 'checked' : '' ?>>
                        <div class="editor-toggle-content">
                            <div class="editor-toggle-title">Featured Project</div>
                            <div class="editor-toggle-desc">Display on homepage featured section</div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div class="editor-section">
            <div class="editor-section-header">
                <div class="editor-section-icon purple">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <span class="editor-section-title">Team & Tech</span>
            </div>
            <div class="editor-section-body">
                <div class="editor-field">
                    <label class="editor-label">Authors</label>
                    <div class="editor-checkbox-group">
                        <?php foreach ($teamMembers as $member): ?>
                            <label class="editor-checkbox <?= in_array($member['id'], $selectedAuthors ?? []) ? 'checked' : '' ?>">
                                <input type="checkbox" name="authors[]" value="<?= $member['id'] ?>"
                                    <?= in_array($member['id'], $selectedAuthors ?? []) ? 'checked' : '' ?>>
                                <span class="editor-checkbox-label"><?= htmlspecialchars($member['name']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="editor-field" style="margin-top: 20px;">
                    <label class="editor-label">Tech Stack</label>
                    <div class="editor-checkbox-group">
                        <?php foreach ($techStack as $tech): ?>
                            <label class="editor-checkbox <?= in_array($tech['id'], $selectedTech ?? []) ? 'checked' : '' ?>">
                                <input type="checkbox" name="tech_stack[]" value="<?= $tech['id'] ?>"
                                    <?= in_array($tech['id'], $selectedTech ?? []) ? 'checked' : '' ?>>
                                <span class="editor-checkbox-label"><?= htmlspecialchars($tech['name']) ?></span>
                                <span class="editor-checkbox-meta"><?= htmlspecialchars($tech['tier_name']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="editor-section">
            <div class="editor-section-header">
                <div class="editor-section-icon accent">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                </div>
                <span class="editor-section-title">Gallery</span>
            </div>
            <div class="editor-section-body" style="padding: 0;">
                <div class="gallery-manager">
                    <div class="gallery-header">
                        <span class="gallery-title">Project Images</span>
                        <span class="gallery-hint">First image is thumbnail</span>
                    </div>
                    <div class="gallery-grid" id="gallery-preview">
                        <?php foreach ($gallery ?? [] as $i => $media): ?>
                            <div class="gallery-item">
                                <input type="hidden" name="gallery[]" value="<?= $media['id'] ?>">
                                <img src="<?= $media['path'] ?>" alt="">
                                <?php if ($i === 0): ?>
                                    <span class="gallery-item-badge">Thumb</span>
                                <?php endif; ?>
                                <button type="button" class="gallery-item-remove remove-media">×</button>
                            </div>
                        <?php endforeach; ?>
                        <label class="gallery-upload">
                            <input type="file" id="gallery-upload" accept="image/*" style="display: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                            <span>Add</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="editor-section">
            <div class="editor-section-header">
                <div class="editor-section-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                </div>
                <span class="editor-section-title">Resources & Links</span>
            </div>
            <div class="editor-section-body" style="padding: 0;">
                <div class="resources-manager">
                    <div class="resources-list" id="resources-list">
                        <?php if (empty($resources)): ?>
                            <div class="resources-empty">No resources added yet</div>
                        <?php else: ?>
                            <?php foreach ($resources ?? [] as $index => $resource): ?>
                                <div class="resource-item">
                                    <input type="hidden" name="resources[<?= $index ?>][type]" value="<?= $resource['type'] ?>">
                                    <div class="resource-item-header">
                                        <span class="resource-type-badge"><?= $resource['type'] ?></span>
                                        <button type="button" class="resource-item-remove remove-resource">×</button>
                                    </div>
                                    <div class="resource-item-body">
                                        <?php if ($resource['type'] === 'link'): ?>
                                            <div class="resource-item-row">
                                                <div class="resource-item-field">
                                                    <span class="resource-item-label">Label</span>
                                                    <input type="text" name="resources[<?= $index ?>][label]" class="resource-item-input" value="<?= htmlspecialchars($resource['label']) ?>" placeholder="Link text">
                                                </div>
                                                <div class="resource-item-field">
                                                    <span class="resource-item-label">URL</span>
                                                    <input type="url" name="resources[<?= $index ?>][url]" class="resource-item-input" value="<?= htmlspecialchars($resource['url']) ?>" placeholder="https://...">
                                                </div>
                                            </div>
                                        <?php elseif ($resource['type'] === 'steam'): ?>
                                            <div class="resource-item-field">
                                                <span class="resource-item-label">Steam App ID</span>
                                                <input type="text" name="resources[<?= $index ?>][app_id]" class="resource-item-input" value="<?= htmlspecialchars($resource['app_id']) ?>" placeholder="e.g. 730">
                                            </div>
                                        <?php elseif ($resource['type'] === 'itch'): ?>
                                            <div class="resource-item-field">
                                                <span class="resource-item-label">Itch.io URL</span>
                                                <input type="url" name="resources[<?= $index ?>][url]" class="resource-item-input" value="<?= htmlspecialchars($resource['url']) ?>" placeholder="https://username.itch.io/game">
                                            </div>
                                        <?php elseif ($resource['type'] === 'youtube'): ?>
                                            <div class="resource-item-field">
                                                <span class="resource-item-label">YouTube Video ID</span>
                                                <input type="text" name="resources[<?= $index ?>][video_id]" class="resource-item-input" value="<?= htmlspecialchars($resource['video_id']) ?>" placeholder="e.g. dQw4w9WgXcQ">
                                            </div>
                                        <?php elseif ($resource['type'] === 'download'): ?>
                                            <div class="resource-item-row">
                                                <div class="resource-item-field">
                                                    <span class="resource-item-label">Label</span>
                                                    <input type="text" name="resources[<?= $index ?>][label]" class="resource-item-input" value="<?= htmlspecialchars($resource['label']) ?>" placeholder="e.g. v1.0">
                                                </div>
                                                <div class="resource-item-field">
                                                    <span class="resource-item-label">File</span>
                                                    <input type="file" class="download-file-input resource-item-input">
                                                    <input type="hidden" name="resources[<?= $index ?>][file_path]" class="download-path-input" value="<?= htmlspecialchars($resource['file_path']) ?>">
                                                    <input type="hidden" name="resources[<?= $index ?>][file_size]" class="download-size-input">
                                                </div>
                                            </div>
                                            <?php if (!empty($resource['file_path'])): ?>
                                                <div class="editor-hint" style="margin-top: var(--space-sm); word-break: break-all;">Current: <?= htmlspecialchars($resource['file_path']) ?></div>
                                            <?php endif; ?>
                                        <?php elseif ($resource['type'] === 'embed'): ?>
                                            <div class="resource-item-field">
                                                <span class="resource-item-label">Label (optional)</span>
                                                <input type="text" name="resources[<?= $index ?>][label]" class="resource-item-input" value="<?= htmlspecialchars($resource['label'] ?? '') ?>" placeholder="Section title">
                                            </div>
                                            <div class="resource-item-field">
                                                <span class="resource-item-label">Embed HTML</span>
                                                <textarea name="resources[<?= $index ?>][html]" class="resource-item-textarea" placeholder="<iframe>...</iframe>"><?= htmlspecialchars($resource['html']) ?></textarea>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="resources-add">
                        <select id="resource-type" class="resources-add-select">
                            <option value="link">Link</option>
                            <option value="steam">Steam</option>
                            <option value="itch">Itch.io</option>
                            <option value="youtube">YouTube</option>
                            <option value="download">Download</option>
                            <option value="embed">Embed</option>
                        </select>
                        <button type="button" id="add-resource" class="btn">Add Resource</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="editor-section">
            <div class="editor-actions">
                <button type="submit" class="btn btn-primary"><?= $project ? 'Update Project' : 'Create Project' ?></button>
                <a href="/admin/projects" class="btn">Cancel</a>
                <?php if ($project): ?>
                    <a href="/admin/projects/<?= $project['id'] ?>/devlogs?from=edit" class="btn btn-devlogs">Manage Devlogs</a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<script src="/js/admin/project-form.js"></script>