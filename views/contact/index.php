<?php
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Contact - VetClinic</title>
    <link rel="stylesheet" href="/vetclinic/public/css/css17.04.css">
    <link rel="stylesheet" href="/vetclinic/public/css/contact.css">
</head>
<body>

    <?php require_once __DIR__ . '/../partials/header.php'; ?>

    <main class="contact-container">
        <div class="contact-header">
            <h1>Contact us</h1>
            <p>Ask a question or leave a review — we will respond as soon as possible</p>
        </div>
        
        <div class="contact-grid">
            <div class="contact-info">
                <div class="info-card">
                    <div class="info-icon">📍</div>
                    <h3>Address</h3>
                    <p>Moscow, Veterinarnaya Street, 15</p>
                </div>
                <div class="info-card">
                    <div class="info-icon">📞</div>
                    <h3>Phone</h3>
                    <p>+7 (999) 123-45-67</p>
                </div>
                <div class="info-card">
                    <div class="info-icon">✉️</div>
                    <h3>Email</h3>
                    <p>info@vetclinic.ru</p>
                </div>
                <div class="info-card">
                    <div class="info-icon">🕐</div>
                    <h3>Word time</h3>
                    <p>Пн-Пт: 09:00 - 20:00</p>
                    <p>Сб-Вс: 10:00 - 18:00</p>
                </div>
            </div>
            
            <div class="contact-form-container">
                <h2>Write to us</h2>
                
                <?php if ($success): ?>
                    <div class="success-message"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="error-message"><?= $error ?></div>
                <?php endif; ?>
                
                <form method="POST" action="/vetclinic/api/contact/send" class="contact-form" id="contactForm">
                    <div class="form-group">
                        <label for="name">Your name *</label>
                        <input type="text" id="name" name="name" 
                               value="<?= htmlspecialchars($userData['name'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" 
                               value="<?= htmlspecialchars($userData['email'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" 
                               value="<?= htmlspecialchars($userData['phone'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn-submit">Send a message</button>
                </form>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>