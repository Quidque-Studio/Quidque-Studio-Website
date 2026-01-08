<?php use Api\Core\Date; ?>

<div class="admin-toolbar">
    <a href="/admin/messages" class="btn">Back to Messages</a>
</div>

<div class="form-section">
    <p style="color: var(--text-muted); margin-bottom: 0;">
        From: <strong style="color: var(--text-primary);"><?= htmlspecialchars($conversation['user_name']) ?></strong>
        (<?= htmlspecialchars($conversation['user_email']) ?>)
    </p>
</div>

<div class="message-list" style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px;">
    <?php foreach ($messages as $msg): ?>
        <div style="max-width: 80%; padding: 16px 20px; border-radius: 12px; <?= $msg['sender_role'] === 'team_member' ? 'background: var(--primary-dim); border: 1px solid rgba(0,255,187,0.3); align-self: flex-end;' : 'background: var(--purple-dim); border: 1px solid rgba(157,126,219,0.3); align-self: flex-start;' ?>">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px;">
                <strong style="color: var(--text-primary);"><?= htmlspecialchars($msg['sender_name']) ?></strong>
                <span style="color: var(--text-muted);"><?= Date::relative($msg['created_at']) ?></span>
            </div>
            <div style="color: var(--text-secondary); line-height: 1.6;"><?= nl2br(htmlspecialchars($msg['content'])) ?></div>
        </div>
    <?php endforeach; ?>
</div>

<form method="POST" action="/admin/messages/<?= $conversation['id'] ?>/reply" class="form-section">
    <?= \Api\Core\View::csrfField() ?>
    <div class="form-group" style="margin-bottom: 16px;">
        <textarea name="content" rows="4" placeholder="Write a reply..." required style="width: 100%; padding: 14px; background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: 8px; color: var(--text-primary); resize: vertical;"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Send Reply</button>
</form>