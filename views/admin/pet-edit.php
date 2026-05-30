<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Edit Pet - Admin Panel</title>
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
                <h1>Edit Pet</h1>
                <a href="/vetclinic/admin/pets" class="btn-back">← Back to list</a>
            </div>
            
            <div class="admin-form-container">
                <form class="admin-form" id="petForm">
                    <input type="hidden" name="id" value="<?= $pet['id'] ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Pet Name *</label>
                            <input type="text" id="name" name="name" value="<?= htmlspecialchars($pet['name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="view">Animal Type *</label>
                            <select id="view" name="view" required>
                                <option value="">Select type</option>
                                <option value="Dog" <?= ($pet['view'] == 'Dog') ? 'selected' : '' ?>>Dog</option>
                                <option value="Cat" <?= ($pet['view'] == 'Cat') ? 'selected' : '' ?>>Cat</option>
                                <option value="Bird" <?= ($pet['view'] == 'Bird') ? 'selected' : '' ?>>Bird</option>
                                <option value="Rodent" <?= ($pet['view'] == 'Rodent') ? 'selected' : '' ?>>Rodent</option>
                                <option value="Reptile" <?= ($pet['view'] == 'Reptile') ? 'selected' : '' ?>>Reptile</option>
                                <option value="Other" <?= ($pet['view'] == 'Other') ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="breed">Breed</label>
                            <input type="text" id="breed" name="breed" value="<?= htmlspecialchars($pet['Breed'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="age">Age (years)</label>
                            <input type="number" id="age" name="age" min="0" step="0.5" value="<?= $pet['Age'] ?? 0 ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="weight">Weight (kg)</label>
                            <input type="number" id="weight" name="weight" step="0.1" value="<?= $pet['weight'] ?? 0 ?>">
                        </div>
                        <div class="form-group">
                            <label for="owner_id">Owner *</label>
                            <select id="owner_id" name="owner_id" required>
                                <option value="">Select owner</option>
                                <?php foreach ($owners as $owner): ?>
                                    <option value="<?= $owner['id'] ?>" <?= ($pet['owner_id'] == $owner['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($owner['firstName'] . ' ' . $owner['secondName'] . ' (' . $owner['email'] . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-buttons">
                        <button type="submit" class="btn-save">Save Changes</button>
                        <button type="button" class="btn-cancel" onclick="location.href='/vetclinic/admin/pets'">Cancel</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('petForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => data[key] = value);
            
            fetch('/vetclinic/api/admin/update-pet', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Pet updated successfully');
                    window.location.href = '/vetclinic/admin/pets';
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