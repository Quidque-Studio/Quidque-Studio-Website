<div class="auth-container">
    <h1>Login</h1>
    
    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/auth/login">
        <?= \Api\Core\View::csrfField() ?>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required autofocus>
        <button type="submit">Send Magic Link</button>
    </form>
</div>