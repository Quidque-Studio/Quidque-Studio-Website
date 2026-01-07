<h1>Quidque Studio</h1>
<p>It works.</p>

<?php if ($user): ?>
    <p>Logged in as: <?= htmlspecialchars($user['name']) ?></p>
<?php endif; ?>