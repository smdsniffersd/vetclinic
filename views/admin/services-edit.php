<?php

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Edit service - Admin panel</title>
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
                    <li><a href="/vetclinic/admin/personal"  >👨‍⚕️ Personal</a></li>
                    <li><a href="/vetclinic/admin/services">💊 Services</a></li>
                    <li><a href="/vetclinic/admin/messages">✉️ Messages</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="admin-content">
            <div class="admin-header">
                <h1>Edit service</h1>
                <a href="/vetclinic/admin/services" class="btn-back">← back to list</a>
            </div>
            
            <div class="admin-form-container">
                <form method="POST" action="/vetclinic/api/admin/update-service" class="admin-form" id="serviceForm">
                    <input type="hidden" name="id" value="<?= $service['id'] ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Name service *</label>
                            <input type="text" id="name" name="name" value="<?= htmlspecialchars($service['name']) ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price ($) *</label>
                            <input type="number" id="price" name="price" step="0.01" value="<?= $service['price'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="doctor_type_id">Dr type (ID) *</label>
                            <select id="doctor_type_id" name="doctor_type_id" required>
                                <option value="">Select Dr type</option>
                                <option value="1" <?= $service['doctor_type_id'] == 1 ? 'selected' : '' ?>>Veterinar</option>
                                <option value="2" <?= $service['doctor_type_id'] == 2 ? 'selected' : '' ?>>Admin</option>
                                <option value="3" <?= $service['doctor_type_id'] == 3 ? 'selected' : '' ?>>Manager</option>
                                <option value="4" <?= $service['doctor_type_id'] == 4 ? 'selected' : '' ?>>Grumer</option>
                                <option value="5" <?= $service['doctor_type_id'] == 5 ? 'selected' : '' ?>>Kernel-personal</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-buttons">
                        <button type="submit" class="btn-save">Save</button>
                        <button type="button" class="btn-cancel" onclick="location.href='/vetclinic/admin/services'">Cancel</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('serviceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => data[key] = value);
            
            fetch('/vetclinic/api/admin/update-service', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Service was updated');
                    window.location.href = '/vetclinic/admin/services';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error with save');
            });
        });
    </script>
</body>
</html>