<div class="messages-container">
    <a href="/messages" class="btn">Back</a>
    <h1><?= htmlspecialchars($conversation['subject']) ?></h1>

    <div class="message-list">
        <?php foreach ($messages as $msg): ?>
            <div class="message <?= $msg['sender_role'] === 'team_member' ? 'message-team' : 'message-user' ?>">
                <div class="message-meta">
                    <strong><?= htmlspecialchars($msg['sender_name']) ?></strong>
                    <span><?= $msg['created_at'] ?></span>
                </div>
                <div class="message-content"><?= nl2br(htmlspecialchars($msg['content'])) ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <form method="POST" action="/messages/<?= $conversation['id'] ?>/reply" class="reply-form">
        <?= \Api\Core\View::csrfField() ?>
        <textarea name="content" rows="4" placeholder="Write a reply..." required></textarea>
        <button type="submit" class="btn btn-primary">Send</button>
    </form>
</div>