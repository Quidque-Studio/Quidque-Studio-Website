<div class="messages-container">
    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= $flash['type'] ?>"><?= htmlspecialchars($flash['message']) ?></div>
    <?php endif; ?>

    <div class="messages-header">
        <h1>My Messages</h1>
        <a href="/messages/new" class="btn btn-primary">New Message</a>
    </div>

    <?php if (empty($conversations)): ?>
        <p>No conversations yet.</p>
    <?php else: ?>
        <ul class="conversation-list">
            <?php foreach ($conversations as $conv): ?>
                <li>
                    <a href="/messages/<?= $conv['id'] ?>">
                        <strong><?= htmlspecialchars($conv['subject']) ?></strong>
                        <span><?= $conv['message_count'] ?> messages</span>
                        <span><?= $conv['updated_at'] ?></span>
                    </a>
                    <form method="POST" action="/messages/<?= $conv['id'] ?>/delete" class="delete-form">
                        <button type="submit" onclick="return confirm('Delete this conversation?')">×</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>