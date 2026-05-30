<?php
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Entries - Admin Panel</title>
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
                    <li><a href="/vetclinic/admin/appointments" class="active">📅 Appointments</a></li>
                    <li><a href="/vetclinic/admin/personal">👨‍⚕️ Personal</a></li>
                    <li><a href="/vetclinic/admin/services">💊 Services</a></li>
                    <li><a href="/vetclinic/admin/messages">✉️ Messages</a></li>
                </ul>
            </nav>
        </aside>

        <main class="admin-content">
            <div class="admin-header">
                <h1>Appointments</h1>
                <p>Customer Record Management</p>
            </div>

            <div class="admin-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Pet</th>
                            <th>Sevice</th>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($appointments)): ?>
                            <tr>
                                <td colspan="9" class="empty-state">There are no records</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($appointments as $apt): ?>
                                <tr>
                                    <td><?= $apt['id'] ?></td>
                                    <td><?= htmlspecialchars($apt['user_name'] ?? '') . ' ' . htmlspecialchars($apt['user_lastname'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($apt['pet_name'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($apt['service_name'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($apt['doctor_name'] ?? '-') ?></td>
                                    <td><?= date('d.m.Y', strtotime($apt['date'])) ?></td>
                                    <td><?= $apt['time'] ?></td>
                                    <td>
                                        <select class="status-select" data-id="<?= $apt['id'] ?>">
                                            <option value="active" <?= $apt['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                            <option value="completed" <?= $apt['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                                            <option value="cancelled" <?= $apt['status'] == 'cancelled' ? 'selected' : '' ?>>Canceled</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn-edit" onclick="location.href='/vetclinic/admin/appointments/edit/<?= $apt['id'] ?>'">✏️</button>
                                        <button class="btn-delete" onclick="deleteAppointment(<?= $apt['id'] ?>)">🗑️</button>
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
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function() {
                const id = this.dataset.id;
                const status = this.value;

                fetch('/vetclinic/api/admin/update-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: id,
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) alert('Ошибка: ' + data.message);
                    });
            });
        });

        function deleteAppointment(id) {
            if (confirm('Удалить запись?')) {
                fetch('/vetclinic/api/admin/delete-appointment', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: id
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) location.reload();
                        else alert('Ошибка: ' + data.message);
                    });
            }
        }
    </script>
</body>

</html>