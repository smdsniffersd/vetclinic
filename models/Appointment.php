<?php

require_once __DIR__ . '/../config.php';

class Appointment
{
    public function findById($id)
    {
        return OneFetch('SELECT * FROM appointments WHERE id = ?', [$id]);
    }

    public function findAll()
    {
        return AllFetch('SELECT * FROM appointments ORDER BY date DESC, time DESC');
    }

    public function getByUserId($userId)
    {
        return AllFetch(
            'SELECT a.*, p.name as pet_name, s.name as service_name 
             FROM appointments a
             LEFT JOIN pets p ON p.id = a.pet_id
             LEFT JOIN services s ON s.id = a.service_id
             WHERE a.user_id = ? 
             ORDER BY a.date DESC, a.time DESC',
            [$userId]
        );
    }

    

    public function getUpcomingByUserId($userId)
    {
        return AllFetch(
            'SELECT a.*, p.name as pet_name, s.name as service_name 
             FROM appointments a
             LEFT JOIN pets p ON p.id = a.pet_id
             LEFT JOIN services s ON s.id = a.service_id
             WHERE a.user_id = ? AND a.date >= CURDATE() AND a.status = "active"
             ORDER BY a.date ASC, a.time ASC',
            [$userId]
        );
    }

    public function getByDate($date)
    {
        return AllFetch('SELECT * FROM appointments WHERE date = ?', [$date]);
    }

    public function getBookedTimes($date)
    {
        $appointments = AllFetch(
            'SELECT TIME_FORMAT(time, "%H:%i") as time FROM appointments WHERE date = ?',
            [$date]
        );
        return array_column($appointments, 'time');
    }

    public function create($data)
    {
        return insertRow('appointments', $data);
    }

    public function update($id, $data)
    {
        unset($data['id']);
        return update('appointments', $data, 'id', $id);
    }

    public function cancel($id)
    {
        return update('appointments', ['status' => 'cancelled'], 'id', $id);
    }

    public function complete($id)
    {
        return update('appointments', ['status' => 'completed'], 'id', $id);
    }

    public function delete($id)
    {
        return deleteRow('appointments', 'id', $id);
    }

    public function isTimeAvailable($date, $time)
    {
        $existing = OneFetch(
            'SELECT id FROM appointments WHERE date = ? AND time = ? AND status != "cancelled"',
            [$date, $time]
        );
        return empty($existing);
    }

    public function countToday()
    {
        $result = OneFetch('SELECT COUNT(*) as count FROM appointments WHERE date = CURDATE()');
        return $result['count'];
    }

    public function countByStatus($status)
    {
        $result = OneFetch('SELECT COUNT(*) as count FROM appointments WHERE status = ?', [$status]);
        return $result['count'];
    }

    
}
