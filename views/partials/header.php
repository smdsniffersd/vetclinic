<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($headerData)) {
    require_once __DIR__ . '/../../controllers/PartialController.php';
    $headerData = PartialController::getHeaderData();
}
?>

<header class="header-main">
    <div class="logo">
        <img class="img_logo" src="/vetclinic/public/image/logo.png" alt="Our Logotip">
        <span class="logo_text">Vetcare</span>
    </div>

    <nav class="header_nav">
        <ul class="header_nav_ul">
            <li><a href="/vetclinic/">Home</a></li>
            <li><a href="/vetclinic/services">Our Service</a></li>
            <li><a href="/vetclinic/about">About Us</a></li>
            <li><a href="/vetclinic/contact">Contact Us</a></li>
        </ul>
    </nav>

    <div class="headerButtons">
        <?php if ($headerData['isLoggedIn']): ?>
            <?php if ($headerData['role_id'] == 1): ?>
                <a href="/vetclinic/admin" class="admin-btn">Admin Panel</a>
            <?php endif; ?>

            <?php if ($headerData['role_id'] == 2): ?>
                <a href="/vetclinic/doctor" class="doctor-btn">Doctor Panel</a>
            <?php endif; ?>

            <a href="/vetclinic/user/account" class="loginBtnText">My Account</a>
            <a href="/vetclinic/auth/logout" class="logoutbutton">Logout</a>
        <?php else: ?>
            <a href="/vetclinic/auth/login" class="loginBtnText">Login</a>
            <a href="/vetclinic/auth/register" class="register-btn">Register</a>
        <?php endif; ?>
    </div>
</header>

<header class="header-mobi">
    <a class="logo" href="/vetclinic/">
        <img class="img_logo" src="/vetclinic/public/image/logo.png" alt="Our Logotip">
        <span class="logo_text">Vetcare</span>
    </a>

    <button class="menu-toggle" id="menuToggle">
        <img src="/vetclinic/public/image/icons8-50.png" alt="menu icon">
    </button>

    <ul class="dropdown-menu" id="dropdownMenu">
        <li><a href="/vetclinic/">Home</a></li>
        <li><a href="/vetclinic/services">Our Service</a></li>
        <li><a href="/vetclinic/about">About Us</a></li>
        <li><a href="/vetclinic/contact">Contact Us</a></li>

        <?php if ($headerData['isLoggedIn']): ?>
            <?php if ($headerData['role_id'] == 1): ?>
                <li><a href="/vetclinic/admin">Admin Panel</a></li>
            <?php endif; ?>
            <?php if ($headerData['role_id'] == 2): ?>
                <li><a href="/vetclinic/doctor">Doctor Panel</a></li>
            <?php endif; ?>
            <li><a href="/vetclinic/user/account">My Account</a></li>
            <li><a href="/vetclinic/auth/logout">Logout</a></li>
        <?php else: ?>
            <li><a href="/vetclinic/auth/login">Login</a></li>
            <li><a href="/vetclinic/auth/register">Register</a></li>
        <?php endif; ?>
    </ul>
</header>
<div class="reminders-panel" id="remindersPanel">
    <div class="reminders-tab">
        <button class="reminders-toggle" onclick="toggleRemindersPanel()">
            <span class="bell-icon">🔔</span>
            <span class="reminders-count" id="remindersCount">0</span>
        </button>
    </div>
    <div class="reminders-content" id="remindersContent" style="display: none;">
        <div class="reminders-header">
            <span>Reminders</span>
            <button class="reminders-close" onclick="toggleRemindersPanel()">✖</button>
        </div>
        <div class="reminders-section">
            <div class="section-title">Upcoming Appointments</div>
            <div id="localAppointmentsList" class="reminders-list"></div>
        </div>
        <div class="reminders-section">
            <div class="section-title">Medications</div>
            <div id="localMedicationsList" class="reminders-list"></div>
        </div>
    </div>
</div>
<script src="/vetclinic/public/js/reminders.js"></script>
<script src="/vetclinic/public/js/booking.js"></script>
<script>
    function toggleRemindersPanel() {
        const content = document.getElementById('remindersContent');
        if (content.style.display === 'none') {
            content.style.display = 'block';
            ReminderManager.renderAppointments();
            ReminderManager.renderMedications();
        } else {
            content.style.display = 'none';
        }
    }

    function updateRemindersCount() {
        const appointments = ReminderManager.getAppointments();
        const medications = ReminderManager.getMedications();
        const now = new Date();

        let count = 0;
        appointments.forEach(apt => {
            const aptDate = new Date(`${apt.date}T${apt.time}`);
            if (aptDate > now && !apt.isNotified) count++;
        });
        medications.forEach(med => {
            const medDate = new Date(med.scheduledTime);
            if (!med.isTaken && medDate >= now && !med.isNotified) count++;
        });

        const countSpan = document.getElementById('remindersCount');
        if (countSpan) {
            countSpan.textContent = count;
            countSpan.style.display = count > 0 ? 'inline-block' : 'none';
        }
    }

    setInterval(updateRemindersCount, 30000);
</script>