<?php
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Register - VetClinic</title>
    <link rel="stylesheet" href="/vetclinic/public/css/css17.04.css">
    <link rel="stylesheet" href="/vetclinic/public/css/auth.css">
</head>
<body>

    <?php require_once __DIR__ . '/../partials/header.php'; ?>

    <main class="auth-container">
        <div class="auth-card">
            <h1>Registration</h1>
            
            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <?php foreach ($errors as $error): ?>
                        <div>• <?= htmlspecialchars($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/vetclinic/auth/register" class="auth-form">
                <div class="form-group">
                    <label for="firstName">Name *</label>
                    <input type="text" id="firstName" name="firstName" 
                           value="<?= htmlspecialchars($oldData['firstName'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="secondName">Last name</label>
                    <input type="text" id="secondName" name="secondName"
                           value="<?= htmlspecialchars($oldData['secondName'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" 
                           value="<?= htmlspecialchars($oldData['email'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone"
                           value="<?= htmlspecialchars($oldData['phone'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="2"><?= htmlspecialchars($oldData['address'] ?? '') ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="password">Password * (min. 6)</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="password_confirm">Password confirmation *</label>
                    <input type="password" id="password_confirm" name="password_confirm" required>
                </div>
                
                <button type="submit" class="btn-primary">Register</button>
            </form>
            
            <p class="auth-link">
                Do you already have an account?<a href="/vetclinic/auth/login">Enter</a>
            </p>
        </div>
    </main>
</body>
</html>