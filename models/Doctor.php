<?php

require_once __DIR__ . '/../config.php';

class Doctor
{

    public function getDoctorInfo($doctorId)
    {
        return OneFetch('SELECT * FROM personal WHERE id = ?', [$doctorId]);
    }

    public function getTodayAppointments($doctorId)
    {
        return AllFetch(
            'SELECT a.*, 
                u.firstName as user_name, u.secondName as user_lastname,
                u.phone as user_phone,
                p.name as pet_name,
                p.view as pet_type,
                p.Breed as pet_breed,
                s.name as service_name,
                s.price as service_price
         FROM appointments a
         LEFT JOIN users u ON u.id = a.user_id
         LEFT JOIN pets p ON p.id = a.pet_id
         LEFT JOIN services s ON s.id = a.service_id
         WHERE a.doctor_id = ? AND a.date = CURDATE() AND a.status != "cancelled"
         ORDER BY a.time ASC',
            [$doctorId]
        );
    }

    public function getUpcomingAppointments($doctorId)
    {
        return AllFetch(
            'SELECT a.*, 
                    u.firstName as user_name, u.secondName as user_lastname,
                    u.phone as user_phone,
                    p.name as pet_name,
                    p.view as pet_type,
                    p.Breed as pet_breed,
                    s.name as service_name,
                    s.price as service_price
             FROM appointments a
             LEFT JOIN users u ON u.id = a.user_id
             LEFT JOIN pets p ON p.id = a.pet_id
             LEFT JOIN services s ON s.id = a.service_id
             WHERE a.doctor_id = ? AND a.date > CURDATE() AND a.status = "active"
             ORDER BY a.date ASC, a.time ASC',
            [$doctorId]
        );
    }

    public function getAppointmentHistory($doctorId, $limit = 20)
    {
        return AllFetch(
            'SELECT a.*, 
                    u.firstName as user_name, u.secondName as user_lastname,
                    p.name as pet_name,
                    s.name as service_name
             FROM appointments a
             LEFT JOIN users u ON u.id = a.user_id
             LEFT JOIN pets p ON p.id = a.pet_id
             LEFT JOIN services s ON s.id = a.service_id
             WHERE a.doctor_id = ? AND (a.date < CURDATE() OR a.status = "completed" OR a.status = "cancelled")
             ORDER BY a.date DESC, a.time DESC
             LIMIT ' . (int)$limit,
            [$doctorId]
        );
    }

    public function getStats($doctorId)
    {
        $stats = [];

        $result = OneFetch(
            'SELECT COUNT(*) as count FROM appointments 
             WHERE doctor_id = ? AND date = CURDATE() AND status != "cancelled"',
            [$doctorId]
        );
        $stats['today_appointments'] = $result['count'];

        $result = OneFetch(
            'SELECT COUNT(*) as count FROM appointments 
             WHERE doctor_id = ? AND date > CURDATE() AND status = "active"',
            [$doctorId]
        );
        $stats['upcoming_appointments'] = $result['count'];

        $result = OneFetch(
            'SELECT COUNT(*) as count FROM appointments 
             WHERE doctor_id = ? AND status = "completed"',
            [$doctorId]
        );
        $stats['completed_appointments'] = $result['count'];

        return $stats;
    }

    public function updateAppointmentStatus($appointmentId, $status)
    {
        return update('appointments', ['status' => $status], 'id', $appointmentId);
    }

    public function addAppointmentNote($appointmentId)
    {
        return ['success' => true];
    }
}
