<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prescribe Treatment - Doctor Panel</title>
    <link rel="stylesheet" href="/vetclinic/public/css/css17.04.css">
    <link rel="stylesheet" href="/vetclinic/public/css/doctor.css">
</head>
<body>

    <?php require_once __DIR__ . '/../partials/header.php'; ?>

    <div class="prescribe-container">
        <div class="prescribe-header">
            <h1>Prescribe Treatment</h1>
            <a href="/vetclinic/doctor" class="btn-back">← Back to Appointments</a>
        </div>
        
        <div class="appointment-info-card">
            <h3>Appointment Information</h3>
            <p><strong>Client:</strong> <?= htmlspecialchars($appointment['user_name'] . ' ' . $appointment['user_lastname']) ?></p>
            <p><strong>Pet:</strong> <?= htmlspecialchars($appointment['pet_name']) ?></p>
            <p><strong>Date:</strong> <?= $appointment['date'] ?> at <?= $appointment['time'] ?></p>
        </div>
        
        <div class="prescribe-form-container">
            <h3>Add Prescription</h3>
            
            <div class="prescribe-type-selector">
                <button type="button" class="type-btn active" data-type="medicine">Medicine</button>
                <button type="button" class="type-btn" data-type="service">Procedure</button>
            </div>
            
            <form id="prescribeForm">
                <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                <input type="hidden" name="pet_id" value="<?= $appointment['pet_id'] ?>">
                <input type="hidden" name="type" id="prescribeType" value="medicine">
                
                <div class="form-group" id="medicineGroup">
                    <label for="medicine_id">Medicine</label>
                    <select id="medicine_id" name="item_id" required>
                        <option value="">Select medicine</option>
                        <?php foreach ($medicines as $med): ?>
                            <option value="<?= $med['id'] ?>"><?= htmlspecialchars($med['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group" id="serviceGroup" style="display: none;">
                    <label for="service_id">Procedure</label>
                    <select id="service_id" name="item_id">
                        <option value="">Select procedure</option>
                        <?php foreach ($services as $serv): ?>
                            <option value="<?= $serv['id'] ?>"><?= htmlspecialchars($serv['name']) ?> (<?= $serv['price'] ?> $)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="scheduled_datetime">Scheduled Date & Time *</label>
                    <input type="datetime-local" id="scheduled_datetime" name="scheduled_datetime" required>
                </div>
                
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Dosage, instructions, special remarks..."></textarea>
                </div>
                
                <button type="submit" class="btn-save">Add Prescription</button>
            </form>
        </div>
        
        <div class="prescriptions-list">
            <h3>Prescriptions for this Appointment</h3>
            <div id="prescriptionsContainer">
                <div class="loading">Loading...</div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.type-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const type = this.dataset.type;
                document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('prescribeType').value = type;
                
                if (type === 'medicine') {
                    document.getElementById('medicineGroup').style.display = 'block';
                    document.getElementById('serviceGroup').style.display = 'none';
                    document.getElementById('medicine_id').required = true;
                    document.getElementById('service_id').required = false;
                } else {
                    document.getElementById('medicineGroup').style.display = 'none';
                    document.getElementById('serviceGroup').style.display = 'block';
                    document.getElementById('medicine_id').required = false;
                    document.getElementById('service_id').required = true;
                }
            });
        });
        
        document.getElementById('prescribeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => data[key] = value);
            
            console.log('Sending data:', data);
            
            fetch('/vetclinic/api/doctor/save-prescription', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Server response:', data);
                if (data.success) {
                    alert('Prescription added');
                    document.getElementById('prescribeForm').reset();
                    loadPrescriptions();
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Submission error: ' + error.message);
            });
        });
        
        function loadPrescriptions() {
            const appointmentId = <?= $appointment['id'] ?>;
            
            fetch(`/vetclinic/api/doctor/get-prescriptions?appointment_id=${appointmentId}`)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('prescriptionsContainer');
                    if (data.success && data.data.length > 0) {
                        container.innerHTML = data.data.map(p => `
                            <div class="prescription-card" data-id="${p.id}">
                                <div class="prescription-header">
                                    <span class="prescription-type">${p.type === 'medicine' ? 'Medicine' : 'Procedure'}</span>
                                    <span class="prescription-date">${p.scheduled_datetime}</span>
                                </div>
                                <div class="prescription-name">
                                    ${p.type === 'medicine' ? (p.medicine_name || 'No name') : (p.service_name || 'No name')}
                                </div>
                                ${p.notes ? `<div class="prescription-notes">${p.notes}</div>` : ''}
                                <div class="prescription-status">
                                    <label>
                                        <input type="checkbox" ${p.is_taken ? 'checked' : ''} 
                                               onchange="updateStatus(${p.id}, this.checked)">
                                        Completed
                                    </label>
                                    <button class="btn-delete" onclick="deletePrescription(${p.id})">🗑️ Delete</button>
                                </div>
                            </div>
                        `).join('');
                    } else {
                        container.innerHTML = '<div class="empty-state">No prescriptions for this appointment</div>';
                    }
                })
                .catch(error => {
                    console.error('Loading error:', error);
                    container.innerHTML = '<div class="empty-state">Error loading data</div>';
                });
        }
        
        function updateStatus(id, isTaken) {
            fetch('/vetclinic/api/doctor/update-prescription-status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id, is_taken: isTaken ? 1 : 0 })
            });
        }
        
        function deletePrescription(id) {
            if (confirm('Delete this prescription?')) {
                fetch('/vetclinic/api/doctor/delete-prescription', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                })
                .then(() => loadPrescriptions());
            }
        }
        
        loadPrescriptions();
    </script>
</body>
</html>