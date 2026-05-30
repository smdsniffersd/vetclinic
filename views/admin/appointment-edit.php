<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Edit Appointment - Admin Panel</title>
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
                <h1>Edit Appointment</h1>
                <a href="/vetclinic/admin/appointments" class="btn-back">← Back to list</a>
            </div>
            
            <div class="admin-form-container">
                <form class="admin-form" id="appointmentForm">
                    <input type="hidden" name="id" value="<?= $appointment['id'] ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="user_id">Client *</label>
                            <select id="user_id" name="user_id" required>
                                <option value="">Select client</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user['id'] ?>" <?= ($appointment['user_id'] == $user['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($user['firstName'] . ' ' . $user['secondName'] . ' (' . $user['email'] . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pet_id">Pet *</label>
                            <select id="pet_id" name="pet_id" required>
                                <option value="">Select pet</option>
                                <?php foreach ($pets as $pet): ?>
                                    <option value="<?= $pet['id'] ?>" <?= ($appointment['pet_id'] == $pet['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($pet['name'] . ' (' . ($pet['owner_name'] ?? 'Unknown') . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="service_id">Service *</label>
                            <select id="service_id" name="service_id" required>
                                <option value="">Select service</option>
                                <?php foreach ($services as $service): ?>
                                    <option value="<?= $service['id'] ?>" <?= ($appointment['service_id'] == $service['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($service['name']) ?> - <?= number_format($service['price'], 0, ',', ' ') ?> ₽
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="doctor_id">Doctor</label>
                            <select id="doctor_id" name="doctor_id">
                                <option value="">Select doctor</option>
                                <?php foreach ($doctors as $doctor): ?>
                                    <option value="<?= $doctor['id'] ?>" <?= ($appointment['doctor_id'] == $doctor['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($doctor['first_name'] . ' ' . $doctor['second_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="date">Date *</label>
                            <input type="date" id="date" name="date" value="<?= $appointment['date'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="time">Time *</label>
                            <input type="time" id="time" name="time" value="<?= $appointment['time'] ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select id="status" name="status" required>
                                <option value="active" <?= ($appointment['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                                <option value="completed" <?= ($appointment['status'] == 'completed') ? 'selected' : '' ?>>Completed</option>
                                <option value="cancelled" <?= ($appointment['status'] == 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-buttons">
                        <button type="submit" class="btn-save">Save Changes</button>
                        <button type="button" class="btn-cancel" onclick="location.href='/vetclinic/admin/appointments'">Cancel</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Автоматическая загрузка питомцев при смене пользователя
        document.getElementById('user_id').addEventListener('change', function() {
            const userId = this.value;
            const petSelect = document.getElementById('pet_id');
            
            if (userId) {
                fetch('/vetclinic/api/admin/get-pets-by-user?user_id=' + userId)
                    .then(response => response.json())
                    .then(data => {
                        petSelect.innerHTML = '<option value="">Select pet</option>';
                        if (data.pets) {
                            data.pets.forEach(pet => {
                                petSelect.innerHTML += `<option value="${pet.id}">${pet.name}</option>`;
                            });
                        }
                    });
            } else {
                petSelect.innerHTML = '<option value="">Select pet</option>';
            }
        });
        
        document.getElementById('appointmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => data[key] = value);
            
            fetch('/vetclinic/api/admin/update-appointment', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Appointment updated successfully');
                    window.location.href = '/vetclinic/admin/appointments';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving data');
            });
        });
    </script>
</body>
</html>