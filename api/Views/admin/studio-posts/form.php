<form method="POST" action="<?= $post ? "/admin/studio-posts/{$post['id']}" : '/admin/studio-posts' ?>">
    <?= \Api\Core\View::csrfField() ?>
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="category_id">Category</label>
        <select id="category_id" name="category_id">
            <option value="">None</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($post['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="tags">Tags (comma separated)</label>
        <input type="text" id="tags" name="tags" value="<?= htmlspecialchars(implode(', ', json_decode($post['tags'] ?? '[]', true))) ?>">
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
                    <option value="quote">Quote</option>
                    <option value="list">List</option>
                    <option value="callout">Callout</option>
                    <option value="video">Video</option>
                    <option value="divider">Divider</option>
                </select>
                <button type="button" id="add-block" class="btn">Add Block</button>
            </div>
        </div>
        <input type="hidden" name="content" id="content-json" value="<?= htmlspecialchars($post['content'] ?? '[]') ?>">
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= $post ? 'Update' : 'Create' ?> Post</button>
        <a href="/admin/studio-posts" class="btn">Cancel</a>
    </div>
</form>

<script src="/js/admin/block-editor.js"></script>