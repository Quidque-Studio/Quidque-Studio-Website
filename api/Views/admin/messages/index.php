<?php use Api\Core\Date; ?>

<div class="admin-table-search">
    <input type="text" id="messages-search" placeholder="Search messages..." autocomplete="off">
</div>

<table class="admin-table admin-table-desktop" id="messages-table">
    <thead>
        <tr>
            <th>Subject</th>
            <th>From</th>
            <th>Messages</th>
            <th>Last Update</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($conversations)): ?>
            <tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 40px;">No messages yet.</td></tr>
        <?php else: ?>
            <?php foreach ($conversations as $conv): ?>
                <tr data-href="/admin/messages/<?= $conv['id'] ?>" class="clickable-row">
                    <td style="font-weight: 500;"><?= htmlspecialchars($conv['subject']) ?></td>
                    <td><?= htmlspecialchars($conv['user_name']) ?></td>
                    <td>
                        <span class="badge"><?= $conv['message_count'] ?></span>
                    </td>
                    <td><?= Date::relative($conv['updated_at']) ?></td>
                    <td class="actions" onclick="event.stopPropagation()">
                        <a href="/admin/messages/<?= $conv['id'] ?>">View</a>
                        <form method="POST" action="/admin/messages/<?= $conv['id'] ?>/delete" style="display:inline">
                            <?= \Api\Core\View::csrfField() ?>
                            <button type="submit" onclick="return confirm('Delete this conversation and all messages?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<div class="mobile-card-view" id="messages-cards">
    <?php if (empty($conversations)): ?>
        <div class="mobile-card" style="text-align: center; color: var(--text-muted); padding: 40px;">
            No messages yet.
        </div>
    <?php else: ?>
        <?php foreach ($conversations as $conv): ?>
            <div class="mobile-card" data-search="<?= htmlspecialchars(strtolower($conv['subject'] . ' ' . $conv['user_name'])) ?>">
                <a href="/admin/messages/<?= $conv['id'] ?>" class="mobile-card-link">
                    <div class="mobile-card-title"><?= htmlspecialchars($conv['subject']) ?></div>
                    <div class="mobile-card-meta">
                        <span><?= htmlspecialchars($conv['user_name']) ?></span>
                        <span class="badge" style="font-size: 0.625rem;"><?= $conv['message_count'] ?> msg</span>
                        <span style="margin-left: auto;"><?= Date::relative($conv['updated_at']) ?></span>
                    </div>
                </a>
                <div class="mobile-card-actions">
                    <a href="/admin/messages/<?= $conv['id'] ?>">View</a>
                    <form method="POST" action="/admin/messages/<?= $conv['id'] ?>/delete" style="display:contents">
                        <?= \Api\Core\View::csrfField() ?>
                        <button type="submit" onclick="return confirm('Delete this conversation and all messages?')">Delete</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', function() {
            window.location.href = this.dataset.href;
        });
    });

    const searchInput = document.getElementById('messages-search');
    const table = document.getElementById('messages-table');
    const cards = document.getElementById('messages-cards');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            
            if (table) {
                table.querySelectorAll('tbody tr').forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            }
            
            if (cards) {
                cards.querySelectorAll('.mobile-card[data-search]').forEach(card => {
                    const text = card.dataset.search;
                    card.style.display = text.includes(query) ? '' : 'none';
                });
            }
        });
    }
});
</script>