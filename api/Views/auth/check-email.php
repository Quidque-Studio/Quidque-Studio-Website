<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Your Email - Quidque Studio</title>
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
            
            <div class="auth-success">
                <div class="auth-success-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </div>
                <h1 class="auth-title">Check your email</h1>
                <p>We sent a login link to <strong><?= htmlspecialchars($email) ?></strong></p>
                <p>Click the link in the email to sign in. It expires in 15 minutes.</p>
            </div>
            
            <div class="auth-footer">
                <a href="/auth/login">← Back to login</a>
            </div>
        </div>
    </div>
</body>
</html>