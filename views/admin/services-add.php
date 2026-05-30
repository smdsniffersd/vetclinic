<?php

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Add service - Admin panel</title>
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
                <h1>Add sevice</h1>
                <a href="/vetclinic/admin/services" class="btn-back">← back to list</a>
            </div>
            
            <div class="admin-form-container">
                <form method="POST" action="/vetclinic/api/admin/add-service" class="admin-form" id="serviceForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Service name *</label>
                            <input type="text" id="name" name="name" required placeholder="Например: Вакцинация">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price ($) *</label>
                            <input type="number" id="price" name="price" step="0.01" required placeholder="1000">
                        </div>
                        <div class="form-group">
                            <label for="doctor_type_id">Doctor type (ID) *</label>
                            <select id="doctor_type_id" name="doctor_type_id" required>
                                <option value="">Select doctor type</option>
                                <option value="1">Veterinar</option>
                                <option value="2">Admin</option>
                                <option value="3">Manager</option>
                                <option value="4">Grumer</option>
                                <option value="5">Kernel-personal</option>
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
            
            fetch('/vetclinic/api/admin/add-service', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Service added');
                    window.location.href = '/vetclinic/admin/services';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error with added');
            });
        });
    </script>
</body>
</html>