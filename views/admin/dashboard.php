<?php
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - VetClinic</title>
    <link rel="stylesheet" href="/vetclinic/public/css/css17.04.css">
    <link rel="stylesheet" href="/vetclinic/public/css/admin.css">
</head>
<body>

    <?php require_once __DIR__ . '/../partials/header.php'; ?>

    <div class="admin-layout">
        <aside class="admin-sidebar">
            <nav class="admin-nav">
                <ul>
                    <li><a href="/vetclinic/admin" class="active">📊 Dasboard</a></li>
                    <li><a href="/vetclinic/admin/users">👥 Users</a></li>
                    <li><a href="/vetclinic/admin/pets">🐾 Pets</a></li>
                    <li><a href="/vetclinic/admin/appointments">📅 Appointments</a></li>
                    <li><a href="/vetclinic/admin/personal">👨‍⚕️ Personal</a></li>
                    <li><a href="/vetclinic/admin/services">💊 Service</a></li>
                    <li><a href="/vetclinic/admin/messages">✉️ Messages</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="admin-content">
            <div class="admin-header">
                <h1>Control panel</h1>
                <p>Welcome, administrator!</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-info">
                        <h3><?= $stats['total_users'] ?></h3>
                        <p>All users</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-info">
                        <h3><?= $stats['new_users_today'] ?></h3>
                        <p>New today</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-info">
                        <h3><?= $stats['total_pets'] ?></h3>
                        <p>All pets</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-info">
                        <h3><?= $stats['appointments_today'] ?></h3>
                        <p>Appointments today</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-info">
                        <h3><?= $stats['active_appointments'] ?></h3>
                        <p>Active appointments</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-info">
                        <h3><?= $stats['new_messages'] ?></h3>
                        <p>New messages</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-info">
                        <h3><?= number_format($stats['month_revenue'], 0, ',', ' ') ?> $</h3>
                        <p>Money</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>