if (typeof window.ReminderManager === 'undefined') {
    
    window.ReminderManager = {

        STORAGE_KEYS: {
            APPOINTMENTS: 'vetclinic_appointments',
            MEDICATIONS: 'vetclinic_medications'
        },
        

        saveAppointment: function(appointmentData) {
            let appointments = this.getAppointments();
            
            const newAppointment = {
                id: Date.now(),
                petName: appointmentData.petName,
                service: appointmentData.service,
                doctorName: appointmentData.doctorName,
                date: appointmentData.date,
                time: appointmentData.time,
                createdAt: new Date().toISOString(),
                isNotified: false,
                notifiedAt: null
            };
            
            appointments.push(newAppointment);
            localStorage.setItem(this.STORAGE_KEYS.APPOINTMENTS, JSON.stringify(appointments));
            this.renderAppointments();
            this.checkForReminders();
            return newAppointment.id;
        },
        

        getAppointments: function() {
            const data = localStorage.getItem(this.STORAGE_KEYS.APPOINTMENTS);
            return data ? JSON.parse(data) : [];
        },
        

        saveMedication: function(medicationData) {
            let medications = this.getMedications();
            
            const newMedication = {
                id: Date.now(),
                petName: medicationData.petName,
                medicineName: medicationData.medicineName,
                scheduledTime: medicationData.scheduledTime,
                dosage: medicationData.dosage || 'По назначению врача',
                instructions: medicationData.instructions || '',
                isTaken: false,
                isNotified: false,
                notifiedAt: null,
                createdAt: new Date().toISOString()
            };
            
            medications.push(newMedication);
            localStorage.setItem(this.STORAGE_KEYS.MEDICATIONS, JSON.stringify(medications));
            this.renderMedications();
            this.checkForReminders();
            return newMedication.id;
        },
        

        getMedications: function() {
            const data = localStorage.getItem(this.STORAGE_KEYS.MEDICATIONS);
            return data ? JSON.parse(data) : [];
        },
        
        markAsTaken: function(id) {
            let medications = this.getMedications();
            medications = medications.map(med => {
                if (med.id === id) {
                    return { ...med, isTaken: true, isNotified: true };
                }
                return med;
            });
            localStorage.setItem(this.STORAGE_KEYS.MEDICATIONS, JSON.stringify(medications));
            this.renderMedications();
        },
        
        deleteMedication: function(id) {
            let medications = this.getMedications();
            medications = medications.filter(med => med.id !== id);
            localStorage.setItem(this.STORAGE_KEYS.MEDICATIONS, JSON.stringify(medications));
            this.renderMedications();
        },
        
        deleteAppointment: function(id) {
            let appointments = this.getAppointments();
            appointments = appointments.filter(apt => apt.id !== id);
            localStorage.setItem(this.STORAGE_KEYS.APPOINTMENTS, JSON.stringify(appointments));
            this.renderAppointments();
        },
        
        dismissAppointment: function(id) {
            let appointments = this.getAppointments();
            appointments = appointments.map(apt => {
                if (apt.id === id) {
                    return { ...apt, isNotified: true, notifiedAt: new Date().toISOString() };
                }
                return apt;
            });
            localStorage.setItem(this.STORAGE_KEYS.APPOINTMENTS, JSON.stringify(appointments));
            this.renderAppointments();
        },
        
        checkForReminders: function() {
            const now = new Date();
            const appointments = this.getAppointments();
            const medications = this.getMedications();
            
            let hasNewReminder = false;
            
            appointments.forEach(apt => {
                if (apt.isNotified) return;
                
                const aptDateTime = new Date(`${apt.date}T${apt.time}`);
                const diffMinutes = (aptDateTime - now) / 1000 / 60;
                
                if (diffMinutes > 0 && diffMinutes <= 60) {
                    this.showNotification('📅 Напоминание о записи', 
                        `У вас запись к ветеринару через ${Math.round(diffMinutes)} минут\nПитомец: ${apt.petName}\nУслуга: ${apt.service}`);
                    apt.isNotified = true;
                    apt.notifiedAt = now.toISOString();
                    hasNewReminder = true;
                }
            });
            
            medications.forEach(med => {
                if (med.isNotified || med.isTaken) return;
                
                const medDate = new Date(med.scheduledTime);
                const isToday = medDate.toDateString() === now.toDateString();
                
                if (isToday && !med.isNotified) {
                    this.showNotification('💊 Напоминание о лекарстве', 
                        `Пора дать лекарство питомцу ${med.petName}\nПрепарат: ${med.medicineName}\nДозировка: ${med.dosage}`);
                    med.isNotified = true;
                    med.notifiedAt = now.toISOString();
                    hasNewReminder = true;
                }
            });
            
            if (hasNewReminder) {
                localStorage.setItem(this.STORAGE_KEYS.APPOINTMENTS, JSON.stringify(appointments));
                localStorage.setItem(this.STORAGE_KEYS.MEDICATIONS, JSON.stringify(medications));
                this.renderAppointments();
                this.renderMedications();
            }
        },
        
        showNotification: function(title, body) {
            if (Notification.permission === 'granted') {
                new Notification(title, { body: body, icon: '/vetclinic/public/image/small_lapka.png' });
            } else if (Notification.permission !== 'denied') {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        new Notification(title, { body: body, icon: '/vetclinic/public/image/small_lapka.png' });
                    }
                });
            }
        },
        
        renderAppointments: function() {
            const container = document.getElementById('localAppointmentsList');
            if (!container) return;
            
            const appointments = this.getAppointments();
            const now = new Date();
            
            const upcoming = appointments.filter(apt => {
                const aptDate = new Date(`${apt.date}T${apt.time}`);
                return aptDate > now;
            });
            
            if (upcoming.length === 0) {
                container.innerHTML = '<div class="empty-reminders">Нет предстоящих записей</div>';
                return;
            }
            
            container.innerHTML = upcoming.map(apt => `
                <div class="reminder-item appointment-item" data-id="${apt.id}">
                    <div class="reminder-icon">📅</div>
                    <div class="reminder-content">
                        <div class="reminder-title">${this.escapeHtml(apt.service)}</div>
                        <div class="reminder-details">🐾 ${this.escapeHtml(apt.petName)}</div>
                        <div class="reminder-details">👨‍⚕️ ${this.escapeHtml(apt.doctorName || 'Врач')}</div>
                        <div class="reminder-time">📅 ${apt.date} в ${apt.time}</div>
                    </div>
                    <button class="reminder-dismiss" onclick="ReminderManager.dismissAppointment(${apt.id})">✓</button>
                </div>
            `).join('');
        },
        
        renderMedications: function() {
            const container = document.getElementById('localMedicationsList');
            if (!container) return;
            
            let medications = this.getMedications();
            const now = new Date();
            
            const active = medications.filter(med => {
                const medDate = new Date(med.scheduledTime);
                return !med.isTaken && medDate >= now;
            });
            
            if (active.length === 0) {
                container.innerHTML = '<div class="empty-reminders">Нет активных напоминаний о лекарствах</div>';
                return;
            }
            
            container.innerHTML = active.map(med => `
                <div class="reminder-item medication-item" data-id="${med.id}">
                    <div class="reminder-icon">💊</div>
                    <div class="reminder-content">
                        <div class="reminder-title">${this.escapeHtml(med.medicineName)}</div>
                        <div class="reminder-details">🐾 ${this.escapeHtml(med.petName)}</div>
                        <div class="reminder-details">💊 ${this.escapeHtml(med.dosage)}</div>
                        <div class="reminder-time">⏰ ${new Date(med.scheduledTime).toLocaleString()}</div>
                        ${med.instructions ? `<div class="reminder-notes">📝 ${this.escapeHtml(med.instructions)}</div>` : ''}
                    </div>
                    <div class="reminder-actions">
                        <button class="reminder-take" onclick="ReminderManager.markAsTaken(${med.id})">✅ Принято</button>
                        <button class="reminder-delete" onclick="ReminderManager.deleteMedication(${med.id})">🗑️</button>
                    </div>
                </div>
            `).join('');
        },
        
        escapeHtml: function(str) {
            if (!str) return '';
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        },
        
        init: function() {

            if (this._initialized) return;
            this._initialized = true;
            
            this.renderAppointments();
            this.renderMedications();
            this.checkForReminders();
            

            setInterval(() => {
                this.checkForReminders();
            }, 60000);
            

            setInterval(() => {
                this.renderAppointments();
                this.renderMedications();
            }, 30000);
        }
    };
    

    if (Notification.permission === 'default') {
        Notification.requestPermission();
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (window.ReminderManager && !window.ReminderManager._initialized) {
            window.ReminderManager.init();
        }
    });
}