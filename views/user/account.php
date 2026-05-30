<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Account - <?= htmlspecialchars($user['firstName']) ?></title>
    <link rel="stylesheet" href="/vetclinic/public/css/css17.04.css">
    <link rel="stylesheet" href="/vetclinic/public/css/account.css">

</head>

<body>

    <?php require_once __DIR__ . '/../partials/header.php'; ?>

    <main class="account-container">
        <div class="account-sidebar">
            <div class="user-avatar">
                <div class="avatar-placeholder">
                    <?= strtoupper(substr($user['firstName'], 0, 1)) ?>
                </div>
                <h2><?= htmlspecialchars($user['firstName']) ?> <?= htmlspecialchars($user['secondName']) ?></h2>
                <p class="user-email"><?= htmlspecialchars($user['email']) ?></p>
            </div>

            <nav class="account-nav">
                <ul>
                    <li class="active" data-tab="profile">Profile</li>
                    <li data-tab="pets">My Pets</li>
                    <li data-tab="appointments">Appointments</li>
                </ul>
            </nav>
        </div>

        <div class="account-content">
            <div class="tab-content active" id="tab-profile">
                <div class="profile-header">
                    <h1>Profile</h1>
                    <button class="edit-profile-btn" id="editProfileBtn">✎ Edit</button>
                </div>

                <?php if ($success): ?>
                    <div class="success-message"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="error-message">
                        <?php foreach ($errors as $error): ?>
                            <div>• <?= htmlspecialchars($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div id="profileView" class="profile-view">
                    <div class="info-row">
                        <span class="info-label">First Name:</span>
                        <span class="info-value"><?= htmlspecialchars($user['firstName']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Last Name:</span>
                        <span class="info-value"><?= htmlspecialchars($user['secondName']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value"><?= htmlspecialchars($user['email']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span class="info-value"><?= htmlspecialchars($user['phone'] ?? 'Not specified') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Address:</span>
                        <span class="info-value"><?= htmlspecialchars($user['address'] ?? 'Not specified') ?></span>
                    </div>
                </div>

                <div id="profileEdit" class="profile-edit" style="display: none;">
                    <form method="POST" action="/vetclinic/user/update">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" id="firstName" name="firstName"
                                value="<?= htmlspecialchars($user['firstName']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="secondName">Last Name</label>
                            <input type="text" id="secondName" name="secondName"
                                value="<?= htmlspecialchars($user['secondName']) ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone"
                                value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea id="address" name="address" rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                        </div>
                        <div class="form-buttons">
                            <button type="submit" class="btn-save">Save</button>
                            <button type="button" class="btn-cancel" id="cancelEditBtn">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="tab-content" id="tab-pets">
                <div class="pets-header">
                    <h1>My Pets</h1>
                    <button class="add-pet-btn" id="addPetBtn">+ Add Pet</button>
                </div>

                <?php if (empty($pets)): ?>
                    <div class="empty-state">
                        <p>You don't have any pets yet</p>
                        <button class="add-pet-btn">+ Add your first pet</button>
                    </div>
                <?php else: ?>
                    <div class="pets-grid">
                        <?php foreach ($pets as $pet): ?>
                            <div class="pet-card" data-pet-id="<?= $pet['id'] ?>">
                                <div class="pet-avatar"><?= strtoupper(substr($pet['name'], 0, 1)) ?></div>
                                <div class="pet-info">
                                    <h3><?= htmlspecialchars($pet['name']) ?></h3>
                                    <p><?= htmlspecialchars($pet['view']) ?> • <?= htmlspecialchars($pet['Breed']) ?></p>
                                    <p>Age: <?= $pet['Age'] ?> years • Weight: <?= $pet['weight'] ?> kg</p>
                                    <?php if (!empty($pet['special_marks'])): ?>
                                        <p class="pet-marks">Special marks: <?= htmlspecialchars($pet['special_marks']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="tab-content" id="tab-appointments">
                <h1>My Appointments</h1>

                <div class="appointments-section">
                    <h2>Upcoming Appointments</h2>
                    <?php if (empty($upcomingAppointments)): ?>
                        <p class="empty-message">No upcoming appointments</p>
                    <?php else: ?>
                        <div class="appointments-list">
                            <?php foreach ($upcomingAppointments as $apt): ?>
                                <div class="appointment-card upcoming">
                                    <div class="appointment-date">
                                        <span class="day"><?= date('d', strtotime($apt['date'])) ?></span>
                                        <span class="month"><?= date('M', strtotime($apt['date'])) ?></span>
                                    </div>
                                    <div class="appointment-info">
                                        <div class="appointment-service"><?= htmlspecialchars($apt['service_name'] ?? 'Consultation') ?></div>
                                        <div class="appointment-pet"><?= htmlspecialchars($apt['pet_name']) ?></div>
                                        <div class="appointment-doctor"><?= htmlspecialchars($apt['doctor_first_name'] ?? '') ?> <?= htmlspecialchars($apt['doctor_second_name'] ?? '') ?></div>
                                    </div>
                                    <div class="appointment-time">
                                        <?= date('H:i', strtotime($apt['time'])) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="appointments-section">
                    <h2>Appointment History</h2>
                    <?php if (empty($historyAppointments)): ?>
                        <p class="empty-message">No appointment history</p>
                    <?php else: ?>
                        <div class="appointments-list">
                            <?php foreach ($historyAppointments as $apt): ?>
                                <div class="appointment-card completed">
                                    <div class="appointment-date">
                                        <span class="day"><?= date('d', strtotime($apt['date'])) ?></span>
                                        <span class="month"><?= date('M', strtotime($apt['date'])) ?></span>
                                    </div>
                                    <div class="appointment-info">
                                        <div class="appointment-service"><?= htmlspecialchars($apt['service_name'] ?? 'Consultation') ?></div>
                                        <div class="appointment-pet">🐾 <?= htmlspecialchars($apt['pet_name']) ?></div>
                                        <div class="appointment-status completed">✓ Completed</div>
                                    </div>
                                    <div class="appointment-time">
                                        <?= date('H:i', strtotime($apt['time'])) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <div class="tab-content" id="tab-messages">
        <h1>My Messages</h1>

        <?php if (empty($userMessages) && empty($userReplies)): ?>
            <p class="empty-message">You have no messages yet</p>
        <?php else: ?>

            <?php if (!empty($userMessages)): ?>
                <div class="messages-section">
                    <h2>Sent Messages</h2>
                    <?php foreach ($userMessages as $msg): ?>
                        <div class="message-card">
                            <div class="message-header">
                                <span class="message-date"><?= date('d.m.Y H:i', strtotime($msg['created_at'])) ?></span>
                                <span class="message-status status-<?= $msg['status'] ?>">
                                    <?php
                                    switch ($msg['status']) {
                                        case 'new':
                                            echo 'New';
                                            break;
                                        case 'read':
                                            echo 'Read';
                                            break;
                                        case 'replied':
                                            echo 'Replied';
                                            break;
                                    }
                                    ?>
                                </span>
                            </div>
                            <div class="message-text"><?= nl2br(htmlspecialchars($msg['message'])) ?></div>
                            <?php if ($msg['reply']): ?>
                                <div class="message-reply">
                                    <strong>Reply:</strong>
                                    <p><?= nl2br(htmlspecialchars($msg['reply'])) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <button class="new-message-btn" onclick="location.href='/vetclinic/contact'">+ New Message</button>
    </div>

    <script src="/vetclinic/public/js/account.js"></script>
</body>

</html>