<?php
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Make an appointment - VetClinic</title>
    <link rel="stylesheet" href="/vetclinic/public/css/css17.04.css">
    <link rel="stylesheet" href="/vetclinic/public/css/booking.css">
</head>
<body>

    <?php require_once __DIR__ . '/../partials/header.php'; ?>

    <main class="booking-container">
        <div class="booking-header">
            <h1>Make an appointment</h1>
            <p>Fill out the form to schedule an appointment for your pet</p>
        </div>

        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST"  class="booking-form" id="bookForm">
            <div class="form-section">
                <h3>Who's going to the reception?</h3>

                <?php if ($headerData['isLoggedIn'] && !empty($userPets)): ?>
                    <div class="pet-option-group">
                        <label class="pet-radio-label">
                            <input type="radio" name="pet_choice" value="existing" checked> Select an existing pet
                        </label>
                        <label class="pet-radio-label">
                            <input type="radio" name="pet_choice" value="new"> Add a new pet
                        </label>
                    </div>

                    <div id="existingPetsContainer" class="pets-selector">
                        <?php foreach ($userPets as $pet): ?>
                            <label class="pet-option">
                                <input type="radio" name="pet_id" value="<?= $pet['id'] ?>">
                                <span class="pet-name"><?= htmlspecialchars($pet['name']) ?></span>
                                <span class="pet-type">(<?= htmlspecialchars($pet['view']) ?>, <?= $pet['Age'] ?> age)</span>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <div id="newPetContainer" class="new-pet-form" style="display: none;">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="new_pet_name">Pet name *</label>
                                <input type="text" id="new_pet_name" name="new_pet_name">
                            </div>
                            <div class="form-group">
                                <label for="new_pet_type">View *</label>
                                <select id="new_pet_type" name="new_pet_type">
                                    <option value="">Select view</option>
                                    <option value="dog">Dog</option>
                                    <option value="cat">Cat</option>
                                    <option value="bird">Bird</option>
                                    <option value="rodent">Rodent</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="new_pet_breed">Breed</label>
                                <input type="text" id="new_pet_breed" name="new_pet_breed">
                            </div>
                            <div class="form-group">
                                <label for="new_pet_age">Age (years)</label>
                                <input type="number" id="new_pet_age" name="new_pet_age" step="0.5" min="0" max="50">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="new_pet_weight">Weitght (kg)</label>
                                <input type="number" id="new_pet_weight" name="new_pet_weight" step="0.1" min="0">
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <input type="hidden" name="pet_choice" value="new">
                    <div class="new-pet-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="new_pet_name">Pet name *</label>
                                <input type="text" id="new_pet_name" name="new_pet_name" required>
                            </div>
                            <div class="form-group">
                                <label for="new_pet_type">View *</label>
                                <select id="new_pet_type" name="new_pet_type" required>
                                    <option value="">Select view</option>
                                    <option value="dog">Dog</option>
                                    <option value="cat">Cat</option>
                                    <option value="bird">Bird</option>
                                    <option value="rodent">Rodent</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="new_pet_breed">Breed</label>
                                <input type="text" id="new_pet_breed" name="new_pet_breed">
                            </div>
                            <div class="form-group">
                                <label for="new_pet_age">Age (years)</label>
                                <input type="number" id="new_pet_age" name="new_pet_age" step="0.5" min="0">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="new_pet_weight">Weight (kg)</label>
                                <input type="number" id="new_pet_weight" name="new_pet_weight" step="0.1" min="0">
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-section">
                <h3>What'll we do?</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="service_id">Service</label>
                        <select id="service_id" name="service_id" required>
                            <option value="">Select service</option>
                            <?php foreach ($services as $service): ?>
                                <option value="<?= $service['id'] ?>">
                                    <?= htmlspecialchars($service['name']) ?> — <?= $service['price'] ?> ₽
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="doctor_id">Doctor</label>
                        <select id="doctor_id" name="doctor_id" required>
                            <option value="">Select doctor</option>
                            <?php foreach ($doctors as $doctor): ?>
                                <option value="<?= $doctor['id'] ?>">
                                    <?= htmlspecialchars($doctor['first_name']) ?> <?= htmlspecialchars($doctor['second_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="specific_condition">Special marks / Symptoms</label>
                    <textarea id="specific_condition" name="specific_condition" rows="4" placeholder="Describe the problem or special requests..."></textarea>
                </div>
            </div>

            <div class="form-section">
                <h3>When is it convenient?</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" id="date" name="date" min="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="time">Time</label>
                        <select id="time" name="time" required>
                            <option value="">Select time</option>
                            <option value="10:00">10:00</option>
                            <option value="10:30">10:30</option>
                            <option value="11:00">11:00</option>
                            <option value="11:30">11:30</option>
                            <option value="12:00">12:00</option>
                            <option value="12:30">12:30</option>
                            <option value="13:00">13:00</option>
                            <option value="13:30">13:30</option>
                            <option value="14:00">14:00</option>
                            <option value="14:30">14:30</option>
                            <option value="15:00">15:00</option>
                            <option value="15:30">15:30</option>
                            <option value="16:00">16:00</option>
                            <option value="16:30">16:30</option>
                            <option value="17:00">17:00</option>
                        </select>
                    </div>
                </div>
            </div>

            <?php if (!$headerData['isLoggedIn']): ?>
                <div class="form-section">
                    <h3>Your contact information</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">Name *</label>
                            <input type="text" id="firstName" name="firstName" required>
                        </div>
                        <div class="form-group">
                            <label for="secondName">Last name</label>
                            <input type="text" id="secondName" name="secondName">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn-submit">Make an appointment</button>
        </form>
    </main>

    <?php require_once __DIR__ . '/../partials/footer.php'; ?>

    <div class="modal-overlay" id="modalOverlay">
        <div class="model-window">
            <img src="/vetclinic/public/image/calendar-model-window-img.png" alt="calendar-model-window-img" class="calendar-model-window-img">
            <div class="model-window-text">
                <span class="model-window-text">Thank you for booking with us!!</span>
                <span class="model-window-text">Your appointment has been successfully </span>
                <span class="model-window-text">scheduled</span>
                <a id="closeModalBtn" href="/vetclinic/user/account">Next</a>
            </div>
        </div>
    </div>
    <div class="NullDateTime" id="NullDateTime">You forgot to choose the time and date!</div>

    <script src="/vetclinic/public/js/booking.js"></script>
    <script src="/vetclinic/public/js/reminders.js"></script>
</body>
</html>