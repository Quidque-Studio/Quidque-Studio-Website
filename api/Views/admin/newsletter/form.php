<form method="POST" action="<?= $newsletter ? "/admin/newsletter/{$newsletter['id']}" : '/admin/newsletter' ?>">
    <div class="form-group">
        <label for="subject">Subject</label>
        <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($newsletter['subject'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="content">Content</label>
        <textarea id="content" name="content" rows="15" required><?= htmlspecialchars($newsletter['content'] ?? '') ?></textarea>
    </div>

    <?php if (!empty($recentContent)): ?>
    <div class="recent-content-reference">
        <h3>Recent Content (for reference)</h3>
        <?php if (!empty($recentContent['projects'])): ?>
            <h4>Projects</h4>
            <ul>
                <?php foreach ($recentContent['projects'] as $p): ?>
                    <li><?= htmlspecialchars($p['title']) ?> (<?= $p['created_at'] ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <?php if (!empty($recentContent['devlogs'])): ?>
            <h4>Devlogs</h4>
            <ul>
                <?php foreach ($recentContent['devlogs'] as $d): ?>
                    <li><?= htmlspecialchars($d['title']) ?> - <?= htmlspecialchars($d['project_title']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <?php if (!empty($recentContent['posts'])): ?>
            <h4>Studio Posts</h4>
            <ul>
                <?php foreach ($recentContent['posts'] as $p): ?>
                    <li><?= htmlspecialchars($p['title']) ?> (<?= $p['created_at'] ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= $newsletter ? 'Update' : 'Save Draft' ?></button>
        <a href="/admin/newsletter" class="btn">Cancel</a>
    </div>
</form>