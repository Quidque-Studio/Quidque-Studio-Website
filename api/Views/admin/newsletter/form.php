<div class="admin-form">
    <h1><?= $newsletter ? 'Edit' : 'New' ?> Newsletter</h1>

    <form method="POST" action="<?= $newsletter ? "/admin/newsletter/{$newsletter['id']}" : '/admin/newsletter' ?>">
        <?= \Api\Core\View::csrfField() ?>

        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($newsletter['subject'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="content">Content</label>
            <div class="markdown-help">
                <strong>Formatting:</strong>
                <code># Heading</code>
                <code>**bold**</code>
                <code>*italic*</code>
                <code>[link text](url)</code>
                <code>---</code> for divider
            </div>
            <textarea id="content" name="content" rows="20" required><?= htmlspecialchars($newsletter['content'] ?? '') ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Draft</button>
            <a href="/admin/newsletter" class="btn">Cancel</a>
            <?php if ($newsletter && !$newsletter['sent_at']): ?>
                <a href="/admin/newsletter/<?= $newsletter['id'] ?>/preview" class="btn">Preview</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<style>
.markdown-help {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    align-items: center;
    margin-bottom: 0.5rem;
    padding: 0.75rem;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 6px;
    font-size: 0.85rem;
}

.markdown-help strong {
    color: var(--text-muted);
}

.markdown-help code {
    background: rgba(157, 126, 219, 0.2);
    padding: 0.2rem 0.4rem;
    border-radius: 3px;
    font-family: monospace;
}

#content {
    font-family: monospace;
    line-height: 1.5;
}
</style>