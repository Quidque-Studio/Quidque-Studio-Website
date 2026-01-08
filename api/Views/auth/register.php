<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <img src="/QuidqueLogo.png" alt="Quidque">
            </div>
            <h1 class="auth-title">Complete your profile</h1>
            <p class="auth-subtitle">Just one more step to get started</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="auth-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/auth/register" class="auth-form">
            <?= \Api\Core\View::csrfField() ?>
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
            
            <div class="auth-field">
                <label for="name">Display name</label>
                <input type="text" id="name" name="name" placeholder="How should we call you?" required autofocus>
            </div>
            <button type="submit" class="auth-submit">Create Account</button>
        </form>
        
        <div class="auth-footer">
            <a href="/">← Back to home</a>
        </div>
    </div>
</div>
