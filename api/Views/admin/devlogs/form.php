<div class="admin-toolbar">
    <a href="/admin/projects/<?= $project['id'] ?>/devlogs" class="btn">Back to Devlogs</a>
</div>

<form method="POST" action="<?= $devlog ? "/admin/projects/{$project['id']}/devlogs/{$devlog['id']}" : "/admin/projects/{$project['id']}/devlogs" ?>">
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($devlog['title'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="tags">Tags (comma separated)</label>
        <input type="text" id="tags" name="tags" value="<?= htmlspecialchars(implode(', ', json_decode($devlog['tags'] ?? '[]', true))) ?>" placeholder="update, bugfix, feature">
    </div>

    <div class="form-group">
        <label>Content</label>
        <div id="block-editor" class="block-editor">
            <div id="blocks-container"></div>
            <div class="block-add">
                <select id="block-type">
                    <option value="text">Text</option>
                    <option value="heading">Heading</option>
                    <option value="image">Image</option>
                    <option value="code">Code</option>
                </select>
                <button type="button" id="add-block" class="btn">Add Block</button>
            </div>
        </div>
        <input type="hidden" name="content" id="content-json" value="<?= htmlspecialchars($devlog['content'] ?? '[]') ?>">
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= $devlog ? 'Update' : 'Create' ?> Devlog</button>
        <a href="/admin/projects/<?= $project['id'] ?>/devlogs" class="btn">Cancel</a>
    </div>
</form>

<script src="/js/admin/block-editor.js"></script>