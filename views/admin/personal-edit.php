<?php

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Edit persoanl - Admin panel</title>
    <link rel="stylesheet" href="/vetclinic/public/css/css17.04.css">
    <link rel="stylesheet" href="/vetclinic/public/css/admin.css">
</head>
<body>

    <?php require_once __DIR__ . '/../partials/header.php'; ?>

    <div class="admin-layout">
        <aside class="admin-sidebar">
            <nav class="admin-nav">
                <ul>
                    <li><a href="/vetclinic/admin">📊 Dashboard</a></li>
                    <li><a href="/vetclinic/admin/users">👥 Users</a></li>
                    <li><a href="/vetclinic/admin/pets">🐾 Pets</a></li>
                    <li><a href="/vetclinic/admin/appointments">📅 Appointments</a></li>
                    <li><a href="/vetclinic/admin/personal">👨‍⚕️ Personal</a></li>
                    <li><a href="/vetclinic/admin/services">💊 Services</a></li>
                    <li><a href="/vetclinic/admin/messages">✉️ Messages</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="admin-content">
            <div class="admin-header">
                <h1>Edit personal</h1>
                <a href="/vetclinic/admin/personal" class="btn-back">← back to  list</a>
            </div>
            
            <div class="admin-form-container">
                <form method="POST" action="/vetclinic/api/admin/update-personal" class="admin-form" id="personalForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $personal['id'] ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">Name *</label>
                            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($personal['first_name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="second_name">Last name *</label>
                            <input type="text" id="second_name" name="second_name" value="<?= htmlspecialchars($personal['second_name']) ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone_number">Phone</label>
                            <input type="tel" id="phone_number" name="phone_number" value="<?= htmlspecialchars($personal['phone_number']) ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($personal['email']) ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea id="address" name="address" rows="2"><?= htmlspecialchars($personal['addres'] ?? '') ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="profession_id">Professions</label>
                            <select id="profession_id" name="profession_id">
                                <option value="">Select profession</option>
                                <option value="1" <?= ($personal['profession_id'] ?? '') == 1 ? 'selected' : '' ?>>Veterinar</option>
                                <option value="2" <?= ($personal['profession_id'] ?? '') == 2 ? 'selected' : '' ?>>Admin</option>
                                <option value="3" <?= ($personal['profession_id'] ?? '') == 3 ? 'selected' : '' ?>>Manager</option>
                                <option value="4" <?= ($personal['profession_id'] ?? '') == 4 ? 'selected' : '' ?>>Grumer</option>
                                <option value="5" <?= ($personal['profession_id'] ?? '') == 5 ? 'selected' : '' ?>>Kernel-personal</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="role_id">Роль</label>
                            <select id="role_id" name="role_id">
                                <option value="">Select role</option>
                                <option value="2" <?= ($personal['role_id'] ?? '') == 2 ? 'selected' : '' ?>>Docotor</option>
                                <option value="5" <?= ($personal['role_id'] ?? '') == 5 ? 'selected' : '' ?>>Grumer</option>
                                <option value="6" <?= ($personal['role_id'] ?? '') == 6 ? 'selected' : '' ?>>Practice-Manager</option>
                                <option value="7" <?= ($personal['role_id'] ?? '') == 7 ? 'selected' : '' ?>>Kernel-personal</option>
                                <option value="8" <?= ($personal['role_id'] ?? '') == 8 ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="birthday">Date of born</label>
                            <input type="date" id="birthday" name="birthday" value="<?= $personal['birthday'] ?? '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="experience_work">Work experience (age)</label>
                            <input type="number" id="experience_work" name="experience_work" min="0" value="<?= $personal['experience_work'] ?? 0 ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="photo">Photo</label>
                            <?php if (!empty($personal['photo'])): ?>
                                <div class="current-photo">
                                    <img src="/vetclinic/public/<?= $personal['photo'] ?>" alt="Фото сотрудника">
                                    <span>Select photo</span>
                                </div>
                            <?php endif; ?>
                            <input type="file" id="photo" name="photo" accept="image/*">
                            <small class="form-hint">Leave it space, if you dont want change photo</small>
                        </div>
                    </div>
                    
                    <div class="form-buttons">
                        <button type="submit" class="btn-save">Save changes</button>
                        <button type="button" class="btn-cancel" onclick="location.href='/vetclinic/admin/personal'">Cancel</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('personalForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('/vetclinic/api/admin/update-personal', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Данные сотрудника обновлены');
                    window.location.href = '/vetclinic/admin/personal';
                } else {
                    alert('Ошибка: ' + (data.message || 'Неизвестная ошибка'));
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Произошла ошибка при сохранении');
            });
        });
    </script>
</body>
</html>