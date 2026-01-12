<?php use Api\Core\Date; ?>

<div class="admin-toolbar">
    <a href="/admin/messages" class="btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Back to Messages
    </a>
    <form method="POST" action="/admin/messages/<?= $conversation['id'] ?>/delete" style="display:inline; margin-left: auto;">
        <?= \Api\Core\View::csrfField() ?>
        <button type="submit" class="btn" onclick="return confirm('Delete this conversation and all messages?')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
            Delete Conversation
        </button>
    </form>
</div>

<div class="form-section">
    <p style="color: var(--text-muted); margin-bottom: 0;">
        From: <strong style="color: var(--text-primary);"><?= htmlspecialchars($conversation['user_name']) ?></strong>
        (<?= htmlspecialchars($conversation['user_email']) ?>)
    </p>
</div>

<div class="message-container">
    <div class="message-list" id="message-list">
        <?php foreach ($messages as $msg): ?>
            <div class="message-item <?= $msg['sender_role'] === 'team_member' ? 'message-outgoing' : 'message-incoming' ?>">
                <div class="message-header">
                    <span class="message-time"><?= Date::relative($msg['created_at']) ?></span>
                    <strong class="message-sender"><?= htmlspecialchars($msg['sender_name']) ?></strong>
                </div>
                <div class="message-content"><?= nl2br(htmlspecialchars($msg['content'])) ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <form method="POST" action="/admin/messages/<?= $conversation['id'] ?>/reply" class="message-form">
        <?= \Api\Core\View::csrfField() ?>
        <div class="message-input-wrapper">
            <textarea name="content" rows="3" placeholder="Write a reply..." required></textarea>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                Send
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageList = document.getElementById('message-list');
    if (messageList) {
        messageList.scrollTop = messageList.scrollHeight;
    }
});
</script>