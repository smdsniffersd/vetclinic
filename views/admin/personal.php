<?php

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Personal - Admin panel</title>
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
                    <li><a href="/vetclinic/admin/personal"  class="active">👨‍⚕️ Personal</a></li>
                    <li><a href="/vetclinic/admin/services">💊 Services</a></li>
                    <li><a href="/vetclinic/admin/messages">✉️ Messages</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="admin-content">
            <div class="admin-header">
                <h1>Сотрудники</h1>
                <button class="btn-add" onclick="location.href='/vetclinic/admin/personal/add'">+ Add personal</button>
            </div>
            
            <div class="admin-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Post</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($personal)): ?>
                            <tr>
                                <td colspan="7" class="empty-state">There are no employees</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($personal as $p): ?>
                                <tr>
                                    <td><?= $p['id'] ?></td>
                                    <td>
                                        <?php if (!empty($p['photo'])): ?>
                                            <img src="/vetclinic/public/<?= $p['photo'] ?>" width="40" height="40" style="border-radius: 50%; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="avatar-placeholder"><?= strtoupper(substr($p['first_name'], 0, 1)) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($p['first_name'] . ' ' . $p['second_name']) ?></td>
                                    <td><?= htmlspecialchars($p['profession_name'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($p['phone_number'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($p['email'] ?? '-') ?></td>
                                    <td>
                                        <button class="btn-edit" onclick="editPersonal(<?= $p['id'] ?>)">✏️</button>
                                        <button class="btn-delete" onclick="deletePersonal(<?= $p['id'] ?>)">🗑️</button>
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
        function deletePersonal(id) {
            if (confirm('Delete personal?')) {
                fetch('/vetclinic/api/admin/delete-personal', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) location.reload();
                    else alert('Error: ' + data.message);
                });
            }
        }
        
        function editPersonal(id) {
            window.location.href = '/vetclinic/admin/personal/edit/' + id;
        }
    </script>
</body>
</html>