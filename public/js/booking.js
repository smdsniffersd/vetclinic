if (typeof window.bookingInitialized === 'undefined') {
    window.bookingInitialized = true;
    
    document.addEventListener('DOMContentLoaded', function() {
        const petChoiceRadios = document.querySelectorAll('input[name="pet_choice"]');
        const existingPetsContainer = document.getElementById('existingPetsContainer');
        const newPetContainer = document.getElementById('newPetContainer');
        
        if (petChoiceRadios.length > 0) {
            petChoiceRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'existing') {
                        if (existingPetsContainer) existingPetsContainer.style.display = 'flex';
                        if (newPetContainer) newPetContainer.style.display = 'none';
                        document.querySelectorAll('#newPetContainer input, #newPetContainer select').forEach(field => {
                            field.required = false;
                        });
                        document.querySelectorAll('input[name="pet_id"]').forEach(radio => {
                            radio.required = true;
                        });
                    } else {
                        if (existingPetsContainer) existingPetsContainer.style.display = 'none';
                        if (newPetContainer) newPetContainer.style.display = 'block';
                        document.querySelectorAll('input[name="pet_id"]').forEach(radio => {
                            radio.required = false;
                        });
                        const newPetName = document.getElementById('new_pet_name');
                        const newPetType = document.getElementById('new_pet_type');
                        if (newPetName) newPetName.required = true;
                        if (newPetType) newPetType.required = true;
                    }
                });
            });
            
            const checkedRadio = document.querySelector('input[name="pet_choice"]:checked');
            if (checkedRadio) {
                checkedRadio.dispatchEvent(new Event('change'));
            }
        }
        
        if (petChoiceRadios.length === 0) {
            const newPetName = document.getElementById('new_pet_name');
            const newPetType = document.getElementById('new_pet_type');
            if (newPetName) newPetName.required = true;
            if (newPetType) newPetType.required = true;
        }
        
        const dateInput = document.getElementById('date');
        const timeSelect = document.getElementById('time');
        
        if (dateInput && timeSelect) {
            dateInput.addEventListener('change', function() {
                const selectedDate = this.value;
                
                if (!selectedDate) return;
                
                timeSelect.disabled = true;
                timeSelect.innerHTML = '<option value="">Загрузка...</option>';
                
                fetch('/vetclinic/api/calendar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ date: selectedDate })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        timeSelect.innerHTML = '<option value="">Выберите время</option>';
                        
                        if (data.free_times && data.free_times.length > 0) {
                            data.free_times.forEach(time => {
                                const option = document.createElement('option');
                                option.value = time;
                                option.textContent = time;
                                timeSelect.appendChild(option);
                            });
                            timeSelect.disabled = false;
                        } else {
                            timeSelect.innerHTML = '<option value="">Нет свободного времени</option>';
                            timeSelect.disabled = true;
                        }
                    } else {
                        timeSelect.innerHTML = '<option value="">Ошибка загрузки</option>';
                        timeSelect.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Ошибка загрузки слотов:', error);
                    timeSelect.innerHTML = '<option value="">Ошибка загрузки</option>';
                    timeSelect.disabled = true;
                });
            });
        }
        
        function getPetName() {
            const selectedPet = document.querySelector('input[name="pet_id"]:checked');
            if (selectedPet) {
                const petOption = selectedPet.closest('.pet-option');
                if (petOption) {
                    const petNameSpan = petOption.querySelector('.pet-name');
                    if (petNameSpan) return petNameSpan.textContent;
                }
                return 'Питомец';
            }
            
            const newPetName = document.getElementById('new_pet_name');
            if (newPetName && newPetName.value) return newPetName.value;
            return 'Питомец';
        }
        
        function getServiceName() {
            const serviceSelect = document.getElementById('service_id');
            if (serviceSelect && serviceSelect.selectedIndex >= 0) {
                const text = serviceSelect.options[serviceSelect.selectedIndex].text;
                return text.split(' —')[0] || 'Приём';
            }
            return 'Приём';
        }
        
        function getDoctorName() {
            const doctorSelect = document.getElementById('doctor_id');
            if (doctorSelect && doctorSelect.selectedIndex >= 0) {
                return doctorSelect.options[doctorSelect.selectedIndex].text || 'Врач';
            }
            return 'Врач';
        }
        
        const bookForm = document.getElementById('bookForm');
        if (bookForm) {
            const newBookForm = bookForm.cloneNode(true);
            bookForm.parentNode.replaceChild(newBookForm, bookForm);
            const finalBookForm = newBookForm;
            
            finalBookForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const date = document.getElementById('date').value;
                const time = document.getElementById('time').value;
                
                if (!date || !time) {
                    const nullDateTime = document.getElementById('NullDateTime');
                    if (nullDateTime) {
                        nullDateTime.style.display = 'block';
                        setTimeout(() => { nullDateTime.style.display = 'none'; }, 3000);
                    }
                    return;
                }
                
                const petChoice = document.querySelector('input[name="pet_choice"]:checked');
                let hasPet = false;
                
                if (petChoice && petChoice.value === 'existing') {
                    const selectedPet = document.querySelector('input[name="pet_id"]:checked');
                    if (!selectedPet) {
                        alert('Выберите питомца');
                        return;
                    }
                    hasPet = true;
                } else if (petChoice && petChoice.value === 'new') {
                    const newPetName = document.getElementById('new_pet_name');
                    const newPetType = document.getElementById('new_pet_type');
                    if (!newPetName || !newPetName.value || !newPetType || !newPetType.value) {
                        alert('Заполните кличку и вид питомца');
                        return;
                    }
                    hasPet = true;
                } else if (petChoiceRadios.length === 0) {
                    const newPetName = document.getElementById('new_pet_name');
                    const newPetType = document.getElementById('new_pet_type');
                    if (!newPetName || !newPetName.value || !newPetType || !newPetType.value) {
                        alert('Заполните кличку и вид питомца');
                        return;
                    }
                    hasPet = true;
                }
                
                if (!hasPet) {
                    alert('Выберите питомца или добавьте нового');
                    return;
                }
                
                const serviceId = document.getElementById('service_id');
                const doctorId = document.getElementById('doctor_id');
                if (!serviceId.value) {
                    alert('Выберите услугу');
                    return;
                }
                if (!doctorId.value) {
                    alert('Выберите врача');
                    return;
                }
                
                const submitBtn = finalBookForm.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn ? submitBtn.textContent : '';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Отправка...';
                }
                
                const formData = new FormData(finalBookForm);
                
                fetch('/vetclinic/api/appointments', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {

                        if (typeof ReminderManager !== 'undefined' && ReminderManager.saveAppointment) {
                            ReminderManager.saveAppointment({
                                petName: getPetName(),
                                service: getServiceName(),
                                doctorName: getDoctorName(),
                                date: date,
                                time: time
                            });
                        }
                        

                        console.log('in data', data);
                        const modal = document.getElementById('modalOverlay');
                        if (modal) {
                            modal.style.display = 'flex';
                            document.body.style.overflow = 'hidden';
                        }else{
                            console.log('not defined modal');
                        }
                        

                        finalBookForm.reset();
                        
                        if (dateInput) dateInput.value = '';
                        if (timeSelect) {
                            timeSelect.innerHTML = '<option value="">Выберите время</option>';
                            timeSelect.disabled = true;
                        }
                        
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.textContent = originalBtnText;
                        }
                    } else {
                        alert('Ошибка: ' + (data.message || 'Неизвестная ошибка'));
                        
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.textContent = originalBtnText;
                        }
                    }
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                    alert('Произошла ошибка при отправке. Попробуйте позже.');
                    
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalBtnText;
                    }
                });
            });
        }
        
        const closeModalBtn = document.getElementById('closeModalBtn');
        if (closeModalBtn) {
            const newCloseBtn = closeModalBtn.cloneNode(true);
            closeModalBtn.parentNode.replaceChild(newCloseBtn, closeModalBtn);
            
            newCloseBtn.addEventListener('click', function() {
                const modal = document.getElementById('modalOverlay');
                if (modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
                window.location.href = '/vetclinic/user/account';
            });
        }
        
        const modalOverlay = document.getElementById('modalOverlay');
        if (modalOverlay) {
            const newModalOverlay = modalOverlay.cloneNode(true);
            modalOverlay.parentNode.replaceChild(newModalOverlay, modalOverlay);
            
            newModalOverlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                    document.body.style.overflow = 'auto';
                    window.location.href = '/vetclinic/user/account';
                }
            });
        }
    });
}