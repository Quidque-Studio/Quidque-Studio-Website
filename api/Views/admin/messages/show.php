<?php use Api\Core\Date; ?>

<div class="conversation-header">
    <a href="/admin/messages" class="btn">Back</a>
    <span>From: <?= htmlspecialchars($conversation['user_name']) ?> (<?= htmlspecialchars($conversation['user_email']) ?>)</span>
</div>

<div class="message-list">
    <?php foreach ($messages as $msg): ?>
        <div class="message <?= $msg['sender_role'] === 'team_member' ? 'message-team' : 'message-user' ?>">
            <div class="message-meta">
                <strong><?= htmlspecialchars($msg['sender_name']) ?></strong>
                <span><?= Date::relative($msg['created_at']) ?></span>
            </div>
            <div class="message-content"><?= nl2br(htmlspecialchars($msg['content'])) ?></div>
        </div>
    <?php endforeach; ?>
</div>

<form method="POST" action="/admin/messages/<?= $conversation['id'] ?>/reply" class="reply-form">
    <?= \Api\Core\View::csrfField() ?>
    <textarea name="content" rows="4" placeholder="Write a reply..." required></textarea>
    <button type="submit" class="btn btn-primary">Send Reply</button>
</form>