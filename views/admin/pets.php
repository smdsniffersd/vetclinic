<?php

?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Pets - Admin panel</title>
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
                    <li><a href="/vetclinic/admin/pets" class="active">🐾 Pets</a></li>
                    <li><a href="/vetclinic/admin/appointments">📅 Appointments</a></li>
                    <li><a href="/vetclinic/admin/personal">👨‍⚕️ Personal</a></li>
                    <li><a href="/vetclinic/admin/services">💊 Services</a></li>
                    <li><a href="/vetclinic/admin/messages">✉️ Messages</a></li>
                </ul>
            </nav>
        </aside>

        <main class="admin-content">
            <div class="admin-header">
                <h1>pets</h1>
                <p>Managing user pets</p>
            </div>

            <div class="admin-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>View</th>
                            <th>Breed</th>
                            <th>Age</th>
                            <th>Weight</th>
                            <th>Owner</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pets)): ?>
                            <tr>
                                <td colspan="8" class="empty-state">No pets</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pets as $pet): ?>
                                <tr>
                                    <td><?= $pet['id'] ?></td>
                                    <td><?= htmlspecialchars($pet['name']) ?></td>
                                    <td><?= htmlspecialchars($pet['view'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($pet['Breed'] ?? '-') ?></td>
                                    <td><?= $pet['Age'] ?? '-' ?> лет</td>
                                    <td><?= $pet['weight'] ?? '-' ?> кг</td>
                                    <td><?= htmlspecialchars($pet['owner_name'] ?? '') . ' ' . htmlspecialchars($pet['owner_lastname'] ?? '') ?></td>
                                    <td>
                                        <button class="btn-edit" onclick="location.href='/vetclinic/admin/pets/edit/<?= $pet['id'] ?>'">✏️</button>
                                        <button class="btn-delete" onclick="deletePet(<?= $pet['id'] ?>)">🗑️</button>
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
        function deletePet(petId) {
            if (confirm('Delete pets?')) {
                fetch('/vetclinic/api/admin/delete-pet', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: petId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) location.reload();
                        else alert('Error: ' + data.message);
                    });
            }
        }
    </script>

</body>

</html>