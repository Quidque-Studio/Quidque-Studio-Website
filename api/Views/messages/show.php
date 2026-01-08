<?php use Api\Core\Date; ?>

<div class="conversation-view">
    <div class="conversation-header">
        <a href="/messages" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Back
        </a>
        <h1><?= htmlspecialchars($conversation['subject']) ?></h1>
    </div>

    <div class="message-list">
        <?php foreach ($messages as $msg): ?>
            <div class="message <?= $msg['sender_role'] === 'team_member' ? 'message-team' : 'message-user' ?>">
                <div class="message-header">
                    <span class="message-sender"><?= htmlspecialchars($msg['sender_name']) ?></span>
                    <span class="message-time"><?= Date::relative($msg['created_at']) ?></span>
                </div>
                <div class="message-body"><?= nl2br(htmlspecialchars($msg['content'])) ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <form method="POST" action="/messages/<?= $conversation['id'] ?>/reply" class="reply-form">
        <?= \Api\Core\View::csrfField() ?>
        <textarea name="content" rows="4" placeholder="Write a reply..." required></textarea>
        <button type="submit" class="btn btn-primary">Send Reply</button>
    </form>
</div>