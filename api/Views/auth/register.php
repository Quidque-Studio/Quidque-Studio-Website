<div class="auth-container">
    <h1>Complete Registration</h1>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/auth/register">
        <?= \Api\Core\View::csrfField() ?>
        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
        
        <label for="name">Display Name</label>
        <input type="text" id="name" name="name" required autofocus>
        
        <button type="submit">Create Account</button>
    </form>
</div>