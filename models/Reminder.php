<?php

require_once __DIR__ . '/../config.php';

class Reminder
{
    private $table = 'medication_reminders';
    
    public function create($data)
    {
        $reminderData = [
            'pet_id' => $data['pet_id'],
            'appointment_id' => $data['appointment_id'] ?? null,
            'medicine_id' => $data['medicine_id'] ?? null,
            'type' => $data['type'] ?? 'medicine',
            'scheduled_datetime' => $data['scheduled_datetime'],
            'is_taken' => $data['is_taken'] ?? 0,
            'notes' => $data['notes'] ?? null
        ];
        
        return insertRow($this->table, $reminderData);
    }
    
    public function getByAppointment($appointmentId)
    {
        return AllFetch(
            'SELECT r.*, 
                    m.name as medicine_name,
                    s.name as service_name
             FROM medication_reminders r
             LEFT JOIN medicines m ON m.id = r.medicine_id
             LEFT JOIN services s ON s.id = r.medicine_id
             WHERE r.appointment_id = ?
             ORDER BY r.scheduled_datetime ASC',
            [$appointmentId]
        );
    }
    
    public function getByPet($petId)
    {
        return AllFetch(
            'SELECT r.*, 
                    m.name as medicine_name,
                    s.name as service_name,
                    a.date as appointment_date
             FROM medication_reminders r
             LEFT JOIN medicines m ON m.id = r.medicine_id
             LEFT JOIN services s ON s.id = r.medicine_id
             LEFT JOIN appointments a ON a.id = r.appointment_id
             WHERE r.pet_id = ?
             ORDER BY r.scheduled_datetime DESC',
            [$petId]
        );
    }
    
    public function updateStatus($id, $isTaken)
    {
        return update($this->table, ['is_taken' => $isTaken], 'id', $id);
    }
    
    public function delete($id)
    {
        return deleteRow($this->table, 'id', $id);
    }
    
    public function getActiveByPet($petId)
    {
        return AllFetch(
            'SELECT r.*, 
                    m.name as medicine_name,
                    s.name as service_name
             FROM medication_reminders r
             LEFT JOIN medicines m ON m.id = r.medicine_id
             LEFT JOIN services s ON s.id = r.medicine_id
             WHERE r.pet_id = ? AND r.scheduled_datetime >= CURDATE() AND r.is_taken = 0
             ORDER BY r.scheduled_datetime ASC',
            [$petId]
        );
    }
}