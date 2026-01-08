<div class="admin-toolbar">
    <a href="/admin/newsletter/<?= $newsletter['id'] ?>/edit" class="btn">Edit</a>
    <?php if (!$newsletter['sent_at']): ?>
        <form method="POST" action="/admin/newsletter/<?= $newsletter['id'] ?>/send" style="display:inline">
            <?= \Api\Core\View::csrfField() ?>
            <button type="submit" class="btn btn-primary" onclick="return confirm('Send this newsletter to all subscribers?')">Send Now</button>
        </form>
    <?php endif; ?>
    <a href="/admin/newsletter" class="btn">Back</a>
</div>

<div class="form-section" style="max-width: 700px; margin-top: 24px;">
    <h2 style="font-size: 14px; color: var(--text-muted); margin-bottom: 16px;">Email Preview</h2>
    <div style="background: #f5f5f5; padding: 32px; border-radius: 8px;">
        <div style="background: #fff; padding: 32px; border-radius: 8px; max-width: 600px; margin: 0 auto; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333;">
            <?= $htmlContent ?>
        </div>
    </div>
</div>

<div class="form-section" style="max-width: 700px; margin-top: 24px;">
    <h2 style="font-size: 14px; color: var(--text-muted); margin-bottom: 16px;">Raw Markdown</h2>
    <pre style="background: var(--bg-surface); padding: 16px; border-radius: 8px; overflow-x: auto; font-size: 13px; white-space: pre-wrap;"><?= htmlspecialchars($newsletter['content']) ?></pre>
</div>