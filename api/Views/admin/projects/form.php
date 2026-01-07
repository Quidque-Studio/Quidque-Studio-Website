<form method="POST" action="<?= $project ? "/admin/projects/{$project['id']}" : '/admin/projects' ?>" enctype="multipart/form-data">
    <?= \Api\Core\View::csrfField() ?>
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($project['title'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4"><?= htmlspecialchars($project['description'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status">
            <?php foreach (['planned', 'in_progress', 'completed', 'on_hold', 'abandoned'] as $status): ?>
                <option value="<?= $status ?>" <?= ($project['status'] ?? '') === $status ? 'selected' : '' ?>>
                    <?= ucfirst(str_replace('_', ' ', $status)) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>
            <input type="checkbox" name="is_featured" <?= ($project['is_featured'] ?? false) ? 'checked' : '' ?>>
            Featured on homepage
        </label>
    </div>

    <div class="form-group">
        <label>Tech Stack</label>
        <div class="checkbox-grid">
            <?php foreach ($techStack as $tech): ?>
                <label>
                    <input type="checkbox" name="tech_stack[]" value="<?= $tech['id'] ?>"
                        <?= in_array($tech['id'], $selectedTech ?? []) ? 'checked' : '' ?>>
                    <?= htmlspecialchars($tech['name']) ?>
                    <small>(<?= htmlspecialchars($tech['tier_name']) ?>)</small>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-group">
        <label>Authors</label>
        <div class="checkbox-grid">
            <?php foreach ($teamMembers as $member): ?>
                <label>
                    <input type="checkbox" name="authors[]" value="<?= $member['id'] ?>"
                        <?= in_array($member['id'], $selectedAuthors ?? []) ? 'checked' : '' ?>>
                    <?= htmlspecialchars($member['name']) ?>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-group" id="gallery-container">
        <label>Gallery (first image = thumbnail)</label>
        <div id="gallery-preview" class="gallery-preview">
            <?php foreach ($gallery ?? [] as $media): ?>
                <div class="gallery-item">
                    <input type="hidden" name="gallery[]" value="<?= $media['id'] ?>">
                    <img src="<?= $media['path'] ?>" alt="">
                    <button type="button" class="remove-media">&times;</button>
                </div>
            <?php endforeach; ?>
        </div>
        <input type="file" id="gallery-upload" accept="image/*,video/*" multiple>
    </div>

    <div class="form-group" id="resources-container">
        <label>Resources</label>
        <div id="resources-list">
            <?php foreach ($resources ?? [] as $index => $resource): ?>
                <div class="resource-item">
                    <input type="hidden" name="resources[<?= $index ?>][type]" value="<?= $resource['type'] ?>">
                    <span class="resource-type-label"><?= $resource['type'] ?></span>
                    <?php if ($resource['type'] === 'link'): ?>
                        <input type="text" name="resources[<?= $index ?>][label]" value="<?= htmlspecialchars($resource['label']) ?>" placeholder="Label">
                        <input type="url" name="resources[<?= $index ?>][url]" value="<?= htmlspecialchars($resource['url']) ?>" placeholder="URL">
                    <?php elseif ($resource['type'] === 'steam'): ?>
                        <input type="text" name="resources[<?= $index ?>][app_id]" value="<?= htmlspecialchars($resource['app_id']) ?>" placeholder="App ID">
                    <?php elseif ($resource['type'] === 'itch'): ?>
                        <input type="url" name="resources[<?= $index ?>][url]" value="<?= htmlspecialchars($resource['url']) ?>" placeholder="URL">
                    <?php elseif ($resource['type'] === 'youtube'): ?>
                        <input type="text" name="resources[<?= $index ?>][video_id]" value="<?= htmlspecialchars($resource['video_id']) ?>" placeholder="Video ID">
                    <?php elseif ($resource['type'] === 'download'): ?>
                        <input type="text" name="resources[<?= $index ?>][label]" value="<?= htmlspecialchars($resource['label']) ?>" placeholder="Label">
                        <input type="text" name="resources[<?= $index ?>][file_path]" value="<?= htmlspecialchars($resource['file_path']) ?>" placeholder="File path">
                    <?php elseif ($resource['type'] === 'embed'): ?>
                        <input type="text" name="resources[<?= $index ?>][label]" value="<?= htmlspecialchars($resource['label'] ?? '') ?>" placeholder="Label">
                        <textarea name="resources[<?= $index ?>][html]" placeholder="HTML"><?= htmlspecialchars($resource['html']) ?></textarea>
                    <?php endif; ?>
                    <button type="button" class="remove-resource">&times;</button>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="resource-add">
            <select id="resource-type">
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

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= $project ? 'Update' : 'Create' ?> Project</button>
        <a href="/admin/projects" class="btn">Cancel</a>
    </div>
</form>

<script src="/js/admin/project-form.js"></script>