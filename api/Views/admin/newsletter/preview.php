<div class="admin-preview">
    <div class="preview-header">
        <h1>Preview: <?= htmlspecialchars($newsletter['subject']) ?></h1>
        <div class="preview-actions">
            <a href="/admin/newsletter/<?= $newsletter['id'] ?>/edit" class="btn">Edit</a>
            <?php if (!$newsletter['sent_at']): ?>
                <form method="POST" action="/admin/newsletter/<?= $newsletter['id'] ?>/send" style="display:inline">
                    <?= \Api\Core\View::csrfField() ?>
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Send this newsletter to all subscribers?')">Send Now</button>
                </form>
            <?php endif; ?>
            <a href="/admin/newsletter" class="btn">Back</a>
        </div>
    </div>

    <div class="preview-frame">
        <div class="preview-email">
            <?= $htmlContent ?>
        </div>
    </div>

    <div class="preview-raw">
        <h3>Raw Markdown</h3>
        <pre><?= htmlspecialchars($newsletter['content']) ?></pre>
    </div>
</div>

<style>
.admin-preview {
    max-width: 900px;
    margin: 0 auto;
}

.preview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.preview-actions {
    display: flex;
    gap: 0.5rem;
}

.preview-frame {
    background: #f5f5f5;
    padding: 2rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.preview-email {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    max-width: 600px;
    margin: 0 auto;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    line-height: 1.6;
    color: #333;
}

.preview-email h1,
.preview-email h2,
.preview-email h3 {
    color: #012a31;
    margin-top: 1.5em;
    margin-bottom: 0.5em;
}

.preview-email h1 { font-size: 24px; }
.preview-email h2 { font-size: 20px; }
.preview-email h3 { font-size: 18px; }

.preview-email p { margin: 1em 0; }

.preview-email a { color: #9d7edb; }

.preview-email hr {
    border: none;
    border-top: 1px solid #ddd;
    margin: 2em 0;
}

.preview-raw {
    background: var(--panel-bg);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 1.5rem;
}

.preview-raw h3 {
    margin-bottom: 1rem;
    color: var(--text-muted);
    font-size: 0.9rem;
}

.preview-raw pre {
    background: rgba(0, 0, 0, 0.2);
    padding: 1rem;
    border-radius: 6px;
    overflow-x: auto;
    white-space: pre-wrap;
    font-size: 0.9rem;
}
</style>