<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribe - Quidque Studio</title>
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
            </div>
            
            <?php if ($success): ?>
                <div class="auth-success">
                    <div class="auth-success-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <h1 class="auth-title">Unsubscribed</h1>
                    <p>You've been removed from the Quidque Studio newsletter.</p>
                    <p>Changed your mind? You can resubscribe anytime in your settings.</p>
                </div>
            <?php else: ?>
                <div class="auth-error" style="text-align: center;">
                    <h1 class="auth-title" style="margin-bottom: 12px;">Unsubscribe Failed</h1>
                    <p><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>
            
            <div class="auth-footer">
                <a href="/">← Back to home</a>
            </div>
        </div>
    </div>
</body>
</html>