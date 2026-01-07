<div class="unsubscribe-page">
    <?php if ($success): ?>
        <h1>Unsubscribed</h1>
        <p>You've been unsubscribed from the Quidque Studio newsletter.</p>
        <p>Changed your mind? <a href="/settings">Resubscribe in settings</a> or visit our <a href="/">homepage</a>.</p>
    <?php else: ?>
        <h1>Unsubscribe Failed</h1>
        <p><?= htmlspecialchars($error) ?></p>
        <p><a href="/">Return to homepage</a></p>
    <?php endif; ?>
</div>

<style>
.unsubscribe-page {
    max-width: 500px;
    margin: 4rem auto;
    text-align: center;
    padding: 2rem;
    background: var(--panel-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
}

.unsubscribe-page h1 {
    margin-bottom: 1rem;
}

.unsubscribe-page a {
    color: var(--purple-color);
}
</style>