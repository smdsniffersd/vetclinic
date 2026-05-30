<?php
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Login - VetClinic</title>
    <link rel="stylesheet" href="/vetclinic/public/css/css17.04.css">
    <link rel="stylesheet" href="/vetclinic/public/css/auth.css">
</head>
<body>

    <?php require_once __DIR__ . '/../partials/header.php'; ?>

    <main class="auth-container">
        <div class="auth-card">
            <h1>Login to your personal account</h1>
            
            <?php if (isset($error) && $error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="/vetclinic/auth/login" class="auth-form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-primary">Enter</button>
            </form>
            
            <p class="auth-link">
                No account?<a href="/vetclinic/auth/register">Register</a>
            </p>
        </div>
    </main>

    <?php require_once __DIR__ . '/../partials/footer.php'; ?>

</body>
</html>