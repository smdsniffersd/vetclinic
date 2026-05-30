<?php

require_once __DIR__ . '/../config.php';

class Admin
{

    public function isAdmin($userId)
    {
        $user = OneFetch('SELECT role_id FROM users WHERE id = ?', [$userId]);
        return $user && $user['role_id'] == 1;
    }
    
    public function getAllUsers()
    {
        return AllFetch('SELECT u.*, r.name as role_name 
                         FROM users u 
                         LEFT JOIN roles r ON r.id = u.role_id 
                         ORDER BY u.id DESC');
    }
    
    public function getAllPets()
    {
        return AllFetch('SELECT p.*, u.firstName as owner_name, u.secondName as owner_lastname 
                         FROM pets p 
                         LEFT JOIN users u ON u.id = p.owner_id 
                         ORDER BY p.id DESC');
    }
    
    public function getAllAppointments()
    {
        return AllFetch('SELECT a.*, 
                                u.firstName as user_name, u.secondName as user_lastname,
                                p.name as pet_name,
                                s.name as service_name,
                                per.first_name as doctor_name
                         FROM appointments a
                         LEFT JOIN users u ON u.id = a.user_id
                         LEFT JOIN pets p ON p.id = a.pet_id
                         LEFT JOIN services s ON s.id = a.service_id
                         LEFT JOIN personal per ON per.id = a.doctor_id
                         ORDER BY a.date DESC, a.time DESC');
    }
    
    public function getAllPersonal()
    {
        return AllFetch('SELECT p.*, pr.name as profession_name 
                         FROM personal p 
                         LEFT JOIN professions pr ON pr.id = p.profession_id 
                         ORDER BY p.id DESC');
    }
    
    public function getAllServices()
    {
        return AllFetch('SELECT * FROM services ORDER BY id');
    }
    
    public function getStats()
    {
        $stats = [];
        
        $result = OneFetch('SELECT COUNT(*) as count FROM users');
        $stats['total_users'] = $result['count'];
        
        $result = OneFetch('SELECT COUNT(*) as count FROM users WHERE DATE(created_at) = CURDATE()');
        $stats['new_users_today'] = $result['count'];
        
        $result = OneFetch('SELECT COUNT(*) as count FROM pets');
        $stats['total_pets'] = $result['count'];
        
        $result = OneFetch('SELECT COUNT(*) as count FROM appointments WHERE date = CURDATE()');
        $stats['appointments_today'] = $result['count'];
        
        $result = OneFetch('SELECT COUNT(*) as count FROM appointments WHERE status = "active"');
        $stats['active_appointments'] = $result['count'];
        
        $result = OneFetch('SELECT COUNT(*) as count FROM messages WHERE status = "new"');
        $stats['new_messages'] = $result['count'];
        
        $result = OneFetch('SELECT SUM(s.price) as total 
                           FROM appointments a 
                           LEFT JOIN services s ON s.id = a.service_id 
                           WHERE MONTH(a.date) = MONTH(CURDATE())');
        $stats['month_revenue'] = $result['total'] ?? 0;
        
        return $stats;
    }
    
    public function changeUserRole($userId, $roleId)
    {
        return update('users', ['role_id' => $roleId], 'id', $userId);
    }
    
    public function toggleUserStatus($userId, $status)
    {
        return update('users', ['is_active' => $status], 'id', $userId);
    }
}