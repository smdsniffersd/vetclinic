<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Doctor Panel - VetClinic</title>
    <link rel="stylesheet" href="/vetclinic/public/css/css17.04.css">
    <link rel="stylesheet" href="/vetclinic/public/css/doctor.css">
</head>

<body>

    <?php require_once __DIR__ . '/../partials/header.php'; ?>

    <div class="doctor-layout">
        <aside class="doctor-sidebar">
            <div class="doctor-profile">
                <div class="doctor-avatar">
                    <?php if (!empty($doctor['photo'])): ?>
                        <img src="/vetclinic/public/<?= $doctor['photo'] ?>" alt="Photo">
                    <?php else: ?>
                        <div class="avatar-placeholder">
                            <?= strtoupper(substr($doctor['first_name'] ?? 'D', 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <h3>Dr. <?= htmlspecialchars($doctor['first_name'] ?? '') ?> <?= htmlspecialchars($doctor['second_name'] ?? '') ?></h3>
                <p><?= htmlspecialchars($doctor['profession_name'] ?? 'Veterinarian') ?></p>
            </div>

            <nav class="doctor-nav">
                <ul>
                    <li class="active" data-tab="today">Today</li>
                    <li data-tab="upcoming">Upcoming</li>
                    <li data-tab="history">History</li>
                </ul>
            </nav>
        </aside>

        <main class="doctor-content">
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['today_appointments'] ?></div>
                    <div class="stat-label">Appointments Today</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['upcoming_appointments'] ?></div>
                    <div class="stat-label">Upcoming</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['completed_appointments'] ?></div>
                    <div class="stat-label">Total Completed</div>
                </div>
            </div>

            <div class="tab-content active" id="tab-today">
                <h2>Today's Appointments</h2>
                <?php if (empty($todayAppointments)): ?>
                    <div class="empty-state">No appointments for today</div>
                <?php else: ?>
                    <div class="appointments-list">
                        <?php foreach ($todayAppointments as $apt): ?>
                            <div class="appointment-card" data-id="<?= $apt['id'] ?>">
                                <div class="appointment-time"><?= date('H:i', strtotime($apt['time'])) ?></div>
                                <div class="appointment-info">
                                    <div class="appointment-service"><?= htmlspecialchars($apt['service_name'] ?? 'Consultation') ?></div>
                                    <div class="appointment-pet">
                                        <?= htmlspecialchars($apt['pet_name']) ?>
                                        (<?= htmlspecialchars($apt['pet_type'] ?? '') ?>)
                                    </div>
                                    <div class="appointment-client">
                                         <?= htmlspecialchars($apt['user_name'] . ' ' . $apt['user_lastname']) ?>
                                    </div>
                                    <div class="appointment-phone">
                                         <?= htmlspecialchars($apt['user_phone'] ?? '') ?>
                                    </div>
                                </div>
                                <div class="appointment-actions">
                                    <?php if ($apt['status'] === 'active'): ?>
                                        <button class="btn-complete" onclick="updateStatus(<?= $apt['id'] ?>, 'completed')">✓ Complete</button>
                                    <?php elseif ($apt['status'] === 'completed'): ?>
                                        <span class="badge-completed">✓ Completed</span>
                                    <?php endif; ?>
                                    <button class="btn-view" onclick="viewDetails(<?= $apt['id'] ?>)">Details</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="tab-content" id="tab-upcoming">
                <h2>Upcoming Appointments</h2>
                <?php if (empty($upcomingAppointments)): ?>
                    <div class="empty-state">No upcoming appointments</div>
                <?php else: ?>
                    <div class="appointments-list">
                        <?php foreach ($upcomingAppointments as $apt): ?>
                            <div class="appointment-card">
                                <div class="appointment-date">
                                    <?= date('d.m', strtotime($apt['date'])) ?>
                                    <span class="day"><?= date('D', strtotime($apt['date'])) ?></span>
                                </div>
                                <div class="appointment-time"><?= date('H:i', strtotime($apt['time'])) ?></div>
                                <div class="appointment-info">
                                    <div class="appointment-service"><?= htmlspecialchars($apt['service_name'] ?? 'Consultation') ?></div>
                                    <div class="appointment-pet"><?= htmlspecialchars($apt['pet_name']) ?></div>
                                    <div class="appointment-client"><?= htmlspecialchars($apt['user_name'] . ' ' . $apt['user_lastname']) ?></div>
                                </div>
                                <div class="appointment-actions">
                                    <button class="btn-prescribe" onclick="location.href='/vetclinic/doctor/prescribe/<?= $apt['id'] ?>'"> Prescribe</button>
                                    <button class="btn-view" onclick="viewDetails(<?= $apt['id'] ?>)">Details</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="tab-content" id="tab-history">
                <h2>📋 Appointment History</h2>
                <?php if (empty($historyAppointments)): ?>
                    <div class="empty-state">No appointment history</div>
                <?php else: ?>
                    <div class="history-list">
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Pet</th>
                                    <th>Service</th>
                                    <th>Client</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historyAppointments as $apt): ?>
                                    <tr>
                                        <td><?= date('d.m.Y', strtotime($apt['date'])) ?></td>
                                        <td><?= date('H:i', strtotime($apt['time'])) ?></td>
                                        <td><?= htmlspecialchars($apt['pet_name']) ?></td>
                                        <td><?= htmlspecialchars($apt['service_name'] ?? 'Consultation') ?></td>
                                        <td><?= htmlspecialchars($apt['user_name'] . ' ' . $apt['user_lastname']) ?></td>
                                        <td>
                                            <?php if ($apt['status'] === 'completed'): ?>
                                                <span class="badge-completed">Completed</span>
                                            <?php elseif ($apt['status'] === 'cancelled'): ?>
                                                <span class="badge-cancelled">Cancelled</span>
                                            <?php else: ?>
                                                <span class="badge-active">Active</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Appointment Details</h3>
                <span class="close-btn" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body" id="detailsBody">
                <div class="loading">Loading...</div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navItems = document.querySelectorAll('.doctor-nav li');
            const tabContents = document.querySelectorAll('.tab-content');

            navItems.forEach(item => {
                item.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');

                    navItems.forEach(nav => nav.classList.remove('active'));
                    this.classList.add('active');

                    tabContents.forEach(content => content.classList.remove('active'));
                    const activeTab = document.getElementById(`tab-${tabId}`);
                    if (activeTab) {
                        activeTab.classList.add('active');
                    }
                });
            });
        });

        function updateStatus(appointmentId, status) {
            if (!confirm('Mark appointment as completed?')) return;

            fetch('/vetclinic/api/doctor/status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: appointmentId,
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Status updated');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function viewDetails(appointmentId) {
            const modal = document.getElementById('detailsModal');
            const detailsBody = document.getElementById('detailsBody');

            detailsBody.innerHTML = '<div class="loading">Loading...</div>';
            modal.style.display = 'flex';

            fetch(`/vetclinic/api/doctor/appointment-details?id=${appointmentId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const apt = data.data;
                        detailsBody.innerHTML = `
                        <div class="details-section">
                            <h4>🐱 Client Information</h4>
                            <p><strong>Name:</strong> ${apt.user_name || '-'} ${apt.user_lastname || ''}</p>
                            <p><strong>Email:</strong> ${apt.user_email || '-'}</p>
                            <p><strong>Phone:</strong> ${apt.user_phone || '-'}</p>
                        </div>
                        <div class="details-section">
                            <h4>🐶 Pet Information</h4>
                            <p><strong>Name:</strong> ${apt.pet_name || '-'}</p>
                            <p><strong>Species:</strong> ${apt.pet_type || '-'}</p>
                            <p><strong>Breed:</strong> ${apt.pet_breed || '-'}</p>
                            <p><strong>Age:</strong> ${apt.pet_age || '-'} years</p>
                            <p><strong>Weight:</strong> ${apt.pet_weight || '-'} kg</p>
                        </div>
                        <div class="details-section">
                            <h4>📋 Appointment Information</h4>
                            <p><strong>Date:</strong> ${apt.date}</p>
                            <p><strong>Time:</strong> ${apt.time}</p>
                            <p><strong>Service:</strong> ${apt.service_name || '-'}</p>
                            <p><strong>Price:</strong> ${apt.service_price || '-'} $</p>
                            <p><strong>Status:</strong> <span class="status-${apt.status === 'active' ? 'active' : (apt.status === 'completed' ? 'completed' : 'cancelled')}">${apt.status === 'active' ? 'Active' : (apt.status === 'completed' ? 'Completed' : 'Cancelled')}</span></p>
                        </div>
                    `;
                    } else {
                        detailsBody.innerHTML = '<div class="error">Error loading details</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    detailsBody.innerHTML = '<div class="error">Error loading details</div>';
                });
        }

        function closeModal() {
            document.getElementById('detailsModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('detailsModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>

    <?php require_once __DIR__ . '/../partials/footer.php'; ?>
</body>

</html>