<?php

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Service - Admin panel</title>
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
                    <li><a href="/vetclinic/admin/services"   class="active">💊 Services</a></li>
                    <li><a href="/vetclinic/admin/messages">✉️ Messages</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="admin-content">
            <div class="admin-header">
                <h1>Services</h1>
                <button class="btn-add" onclick="location.href='/vetclinic/admin/services/add'">+ Add service</button>
            </div>
            
            <div class="admin-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Dr type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($services)): ?>
                            <tr>
                                <td colspan="5" class="empty-state">No services</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($services as $service): ?>
                                <tr>
                                    <td><?= $service['id'] ?></td>
                                    <td><?= htmlspecialchars($service['name']) ?></td>
                                    <td><?= number_format($service['price'], 0, ',', ' ') ?> ₽</td>
                                    <td><?= $service['doctor_type_id'] ?></td>
                                    <td>
                                        <button class="btn-edit" onclick="location.href='/vetclinic/admin/services/edit/<?= $service['id'] ?>'">✏️</button>
                                        <button class="btn-delete" onclick="deleteService(<?= $service['id'] ?>)">🗑️</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        function deleteService(id) {
            if (confirm('Delete service?')) {
                fetch('/vetclinic/api/admin/delete-service', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error with save');
                });
            }
        }
    </script>

</body>
</html>