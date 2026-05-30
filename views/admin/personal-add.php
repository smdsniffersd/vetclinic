<?php
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Add personal- Admin panel</title>
    <link rel="stylesheet" href="/vetclinic/public/css/css17.04.css">
    <link rel="stylesheet" href="/vetclinic/public/css/admin.css">
</head>
<body>

    <?php require_once __DIR__ . '/../partials/header.php'; ?>

    <div class="admin-layout">
        <aside class="admin-sidebar">
            <nav class="admin-nav">
                <ul>
                    <li><a href="/vetclinic/admin">Dasboard</a></li>
                    <li><a href="/vetclinic/admin/users">Users</a></li>
                    <li><a href="/vetclinic/admin/pets">Pets</a></li>
                    <li><a href="/vetclinic/admin/appointments">Appointments</a></li>
                    <li><a href="/vetclinic/admin/personal">Personal</a></li>
                    <li><a href="/vetclinic/admin/services">Services</a></li>
                    <li><a href="/vetclinic/admin/messages">Messages</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="admin-content">
            <div class="admin-header">
                <h1>Add personal</h1>
                <a href="/vetclinic/admin/personal" class="btn-back">← back to list</a>
            </div>
            
            <div class="admin-form-container">
                <form method="POST" action="/vetclinic/api/admin/add-personal" class="admin-form" id="personalForm" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">Name *</label>
                            <input type="text" id="first_name" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="second_name">Last Name *</label>
                            <input type="text" id="second_name" name="second_name" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone_number">Phone</label>
                            <input type="tel" id="phone_number" name="phone_number">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea id="address" name="address" rows="2"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="profession_id">Profession (ID) *</label>
                            <select id="profession_id" name="profession_id" required>
                                <option value="">Select profession</option>
                                <option value="1">Veterinar</option>
                                <option value="2">Admin</option>
                                <option value="3">Meneger</option>
                                <option value="4">Grumer</option>
                                <option value="5">Kernel-personal</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="role_id">Role (ID) *</label>
                            <select id="role_id" name="role_id" required>
                                <option value="">Select role</option>
                                <option value="2">Doctor</option>
                                <option value="5">Grumer</option>
                                <option value="6">Practic-manager</option>
                                <option value="7">Kernel-personal</option>
                                <option value="8">Admin</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="birthday">Born date</label>
                            <input type="date" id="birthday" name="birthday">
                        </div>
                        <div class="form-group">
                            <label for="experience_work">Work experience (age)</label>
                            <input type="number" id="experience_work" name="experience_work" min="0">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="photo">Photo</label>
                            <input type="file" id="photo" name="photo" accept="image/*">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="pass">Password</label>
                        <input type="password" id="pass" name="pass" placeholder="Оставьте пустым для автоматической генерации">
                    </div>
                    
                    <div class="form-buttons">
                        <button type="submit" class="btn-save">Save</button>
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
            
            fetch('/vetclinic/api/admin/add-personal', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Сотрудник добавлен');
                    window.location.href = '/vetclinic/admin/personal';
                } else {
                    alert('Ошибка: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Произошла ошибка при добавлении');
            });
        });
    </script>

</body>
</html>