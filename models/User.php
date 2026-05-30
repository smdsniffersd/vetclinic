<?php

require_once __DIR__ . '/../config.php';

class User
{

    public function findById($id)
    {
        return OneFetch('SELECT * FROM users WHERE id = ?', [$id]);
    }
    
    public function findByEmail($email)
    {
        return OneFetch('SELECT * FROM users WHERE email = ?', [$email]);
    }
    

    public function getPets($userId)
    {
        return AllFetch('SELECT * FROM pets WHERE owner_id = ?', [$userId]);
    }
    
    public function getPetsWithMedicalInfo($userId)
    {
        $pets = $this->getPets($userId);
        
        foreach ($pets as &$pet) {
            $pet['medical'] = AllFetch(
                'SELECT mi.*, m.name as medicine_name, s.name as service_name
                 FROM medical_information mi
                 LEFT JOIN medicines m ON m.id = mi.medicine_id
                 LEFT JOIN services s ON s.id = mi.event_id
                 WHERE mi.pets_id = ?',
                [$pet['id']]
            );
        }
        
        return $pets;
    }
    
    public function getUpcomingAppointments($userId)
    {
        return AllFetch(
            'SELECT a.*, p.name as pet_name, s.name as service_name, 
                    per.first_name as doctor_first_name, per.second_name as doctor_second_name
             FROM appointments a
             LEFT JOIN pets p ON p.id = a.pet_id
             LEFT JOIN services s ON s.id = a.service_id
             LEFT JOIN personal per ON per.id = a.doctor_id
             WHERE a.user_id = ? AND a.date >= CURDATE() AND a.status = "active"
             ORDER BY a.date ASC, a.time ASC',
            [$userId]
        );
    }
    
    public function getAppointmentHistory($userId, $limit = 10)
{
    return AllFetch(
        'SELECT a.*, p.name as pet_name, s.name as service_name
         FROM appointments a
         LEFT JOIN pets p ON p.id = a.pet_id
         LEFT JOIN services s ON s.id = a.service_id
         WHERE a.user_id = ? AND (a.date < CURDATE() OR a.status = "completed")
         ORDER BY a.date DESC, a.time DESC
         LIMIT ' . intval($limit),
        [$userId]
    );
}
    
    public function updateProfile($id, $data)
    {
        $allowedFields = ['firstName', 'secondName', 'phone', 'address'];
        $filteredData = array_intersect_key($data, array_flip($allowedFields));
        
        if (empty($filteredData)) {
            return ['success' => false, 'message' => 'Нет данных для обновления'];
        }
        
        $result = update('users', $filteredData, 'id', $id);
        
        if ($result['success']) {
            foreach ($filteredData as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
        
        return $result;
    }
    
    
    public function authenticate($email, $password)
    {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    public function create($data)
{
    unset($data['password_confirm']);
    
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    
    $data['created_at'] = date('Y-m-d H:i:s');
    
    if (!isset($data['role_id'])) {
        $data['role_id'] = 4;
    }
    
    return insertRow('users', $data);
}
    
    private function validateUserData($data)
    {
        $errors = [];
        
        if (empty($data['firstName']) || strlen($data['firstName']) < 2) {
            $errors[] = 'Имя должно содержать минимум 2 символа';
        }
        
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Введите корректный email';
        }
        
        if (strlen($data['password'] ?? '') < 6) {
            $errors[] = 'Пароль должен содержать минимум 6 символов';
        }
        
        if (($data['password'] ?? '') !== ($data['password_confirm'] ?? '')) {
            $errors[] = 'Пароли не совпадают';
        }
        
        $existing = $this->findByEmail($data['email']);
        if ($existing && ($existing['id'] ?? 0) != ($data['id'] ?? 0)) {
            $errors[] = 'Пользователь с таким email уже существует';
        }
        
        return $errors;
    }
}