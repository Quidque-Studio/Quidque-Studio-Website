<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Quidque Studio</title>
    <link rel="stylesheet" href="/css/variables.css">
    <link rel="stylesheet" href="/css/auth.css">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <img src="/QuidqueLogo.png" alt="Quidque">
                </div>
                <h1 class="auth-title">Welcome back</h1>
                <p class="auth-subtitle">Sign in to your account</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="auth-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="/auth/login" class="auth-form">
                <?= \Api\Core\View::csrfField() ?>
                <div class="auth-field">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" required autofocus>
                </div>
                <button type="submit" class="auth-submit">Send Magic Link</button>
            </form>
            
            <div class="auth-footer">
                <a href="/">← Back to home</a>
            </div>
        </div>
    </div>
</body>
</html>