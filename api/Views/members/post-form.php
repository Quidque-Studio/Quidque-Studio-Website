<div class="member-page">
    <div class="member-header">
        <h1><?= $post ? 'Edit Post' : 'New Post' ?></h1>
    </div>

    <div class="member-content">
        <form method="POST" action="<?= $post ? "/team/{$member['id']}/posts/{$post['id']}" : "/team/{$member['id']}/posts" ?>">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title'] ?? '') ?>" required>
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
                        </select>
                        <button type="button" id="add-block" class="btn">Add Block</button>
                    </div>
                </div>
                <input type="hidden" name="content" id="content-json" value="<?= htmlspecialchars($post['content'] ?? '[]') ?>">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $post ? 'Update' : 'Create' ?></button>
                <a href="/team/<?= $member['id'] ?>/posts" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script src="/js/admin/block-editor.js"></script>