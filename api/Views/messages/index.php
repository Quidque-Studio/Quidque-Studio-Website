<?php use Api\Core\Date; ?>

<div class="messages-page">
    <div class="messages-header">
        <h1 class="messages-title">Messages</h1>
        <a href="/messages/new" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
            New Message
        </a>
    </div>

    <?php if (empty($conversations)): ?>
        <div class="messages-empty">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
            <p>No conversations yet</p>
            <a href="/messages/new" class="btn btn-primary">Start a Conversation</a>
        </div>
    <?php else: ?>
        <div class="conversations-list">
            <?php foreach ($conversations as $conv): ?>
                <div class="conversation-item-wrapper" style="display: flex; gap: 8px;">
                    <a href="/messages/<?= $conv['id'] ?>" class="conversation-item" style="flex: 1;">
                        <div class="conversation-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                        </div>
                        <div class="conversation-content">
                            <div class="conversation-subject"><?= htmlspecialchars($conv['subject']) ?></div>
                            <div class="conversation-meta">
                                <span><?= $conv['message_count'] ?> messages</span>
                                <span><?= Date::relative($conv['updated_at']) ?></span>
                            </div>
                        </div>
                    </a>
                    <form method="POST" action="/messages/<?= $conv['id'] ?>/delete" class="conversation-actions">
                        <?= \Api\Core\View::csrfField() ?>
                        <button type="submit" class="conversation-delete" onclick="return confirm('Delete this conversation?')">×</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>