<div class="admin-toolbar">
    <a href="/admin/newsletter" class="btn">Back to Newsletters</a>
</div>

<div class="form-section" style="max-width: 800px;">
    <form method="POST" action="<?= $newsletter ? "/admin/newsletter/{$newsletter['id']}" : '/admin/newsletter' ?>">
        <?= \Api\Core\View::csrfField() ?>

        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($newsletter['subject'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="content">Content</label>
            <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 12px; padding: 12px; background: var(--bg-surface); border-radius: 8px; font-size: 13px;">
                <span style="color: var(--text-muted);">Formatting:</span>
                <code style="background: var(--purple-dim); padding: 2px 8px; border-radius: 4px; color: var(--purple);"># Heading</code>
                <code style="background: var(--purple-dim); padding: 2px 8px; border-radius: 4px; color: var(--purple);">**bold**</code>
                <code style="background: var(--purple-dim); padding: 2px 8px; border-radius: 4px; color: var(--purple);">*italic*</code>
                <code style="background: var(--purple-dim); padding: 2px 8px; border-radius: 4px; color: var(--purple);">[link](url)</code>
            </div>
            <textarea id="content" name="content" rows="20" required style="font-family: 'SF Mono', Monaco, monospace; font-size: 13px;"><?= htmlspecialchars($newsletter['content'] ?? '') ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Draft</button>
            <?php if ($newsletter && !$newsletter['sent_at']): ?>
                <a href="/admin/newsletter/<?= $newsletter['id'] ?>/preview" class="btn">Preview</a>
            <?php endif; ?>
            <a href="/admin/newsletter" class="btn">Cancel</a>
        </div>
    </form>
</div>