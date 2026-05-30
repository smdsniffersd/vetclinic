<?php
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Users - Admin panel</title>
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
                    <li><a href="/vetclinic/admin/users" class="active">👥 Users</a></li>
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
                <h1>Users</h1>
                <p>System User Management</p>
            </div>

            <div class="admin-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Date of registration</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="7" class="empty-state">No users</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= htmlspecialchars($user['firstName'] . ' ' . $user['secondName']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars($user['phone'] ?? '-') ?></td>
                                    <td>
                                        <select class="role-select" data-user-id="<?= $user['id'] ?>">
                                            <option value="1" <?= $user['role_id'] == 1 ? 'selected' : '' ?>>Admin</option>
                                            <option value="2" <?= $user['role_id'] == 2 ? 'selected' : '' ?>>Docotr</option>
                                            <option value="4" <?= $user['role_id'] == 4 ? 'selected' : '' ?>>Client</option>
                                            <option value="9" <?= $user['role_id'] == 9 ? 'selected' : '' ?>>Guest</option>
                                        </select>
                                    </td>
                                    <td><?= date('d.m.Y', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <button class="btn-edit" onclick="location.href='/vetclinic/admin/users/edit/<?= $user['id'] ?>'">✏️</button>
                                        <button class="btn-delete" onclick="deleteUser(<?= $user['id'] ?>)">🗑️</button>
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
        document.querySelectorAll('.role-select').forEach(select => {
            select.addEventListener('change', function() {
                const userId = this.dataset.userId;
                const roleId = this.value;

                fetch('/vetclinic/api/admin/change-role', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            user_id: userId,
                            role_id: roleId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Role was changed');
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

        function deleteUser(userId) {
            if (confirm('You shure? Users and all her data.')) {
                fetch('/vetclinic/api/admin/delete-user', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: userId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    });
            }
        }
    </script>
</body>

</html>