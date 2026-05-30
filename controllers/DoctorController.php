<?php

require_once __DIR__ . '/../models/Doctor.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Reminder.php';
require_once __DIR__ . '/PartialController.php';

class DoctorController
{
    private $doctorModel;
    private $userModel;
    
    public function __construct()
    {
        $this->doctorModel = new Doctor();
        $this->userModel = new User();
    }
    
    private function checkDoctor()
    {
        if (!isset($_SESSION['userauth']) || $_SESSION['userauth'] !== true) {
            header('Location: /vetclinic/auth/login');
            exit;
        }
        
        $user = $this->userModel->findById($_SESSION['userID']);
        if (!$user || $user['role_id'] != 2) {
            header('Location: /vetclinic/');
            exit;
        }
        
        return $user;
    }
    
    public function dashboard()
    {
        $user = $this->checkDoctor();
        
        $doctor = OneFetch('SELECT * FROM personal WHERE email = ?', [$user['email']]);
        
        if (!$doctor) {
            $doctor = OneFetch('SELECT * FROM personal WHERE id = ?', [$user['id']]);
        }
        
        if (!$doctor) {
            echo "Ошибка: профиль врача не найден в системе";
            exit;
        }
        
        $doctorId = $doctor['id'];
        
        $stats = $this->doctorModel->getStats($doctorId);
        $todayAppointments = $this->doctorModel->getTodayAppointments($doctorId);
        $upcomingAppointments = $this->doctorModel->getUpcomingAppointments($doctorId);
        $historyAppointments = $this->doctorModel->getAppointmentHistory($doctorId, 10);
        
        $headerData = PartialController::getHeaderData();
        
        require_once __DIR__ . '/../views/doctor/dashboard.php';
    }
    
    public function updateAppointmentStatus()
    {
        $this->checkDoctor();
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $appointmentId = (int)($input['id'] ?? 0);
        $status = $input['status'] ?? 'active';
        
        $result = $this->doctorModel->updateAppointmentStatus($appointmentId, $status);
        echo json_encode($result);
        exit;
    }
    
    public function getAppointmentDetails()
    {
        $this->checkDoctor();
        header('Content-Type: application/json');
        
        $id = (int)($_GET['id'] ?? 0);
        $appointment = OneFetch(
            'SELECT a.*, 
                    u.firstName as user_name, u.secondName as user_lastname,
                    u.email as user_email, u.phone as user_phone,
                    p.name as pet_name, p.view as pet_type, p.Breed as pet_breed,
                    p.Age as pet_age, p.weight as pet_weight,
                    s.name as service_name, s.price as service_price
             FROM appointments a
             LEFT JOIN users u ON u.id = a.user_id
             LEFT JOIN pets p ON p.id = a.pet_id
             LEFT JOIN services s ON s.id = a.service_id
             WHERE a.id = ?',
            [$id]
        );
        
        echo json_encode(['success' => true, 'data' => $appointment]);
        exit;
    }
    
    public function prescribeForm($appointmentId)
    {
        $this->checkDoctor();
        
        $appointment = OneFetch(
            'SELECT a.*, p.name as pet_name, p.id as pet_id,
                    u.firstName as user_name, u.secondName as user_lastname
             FROM appointments a
             LEFT JOIN pets p ON p.id = a.pet_id
             LEFT JOIN users u ON u.id = a.user_id
             WHERE a.id = ?',
            [$appointmentId]
        );
        
        if (!$appointment) {
            header('Location: /vetclinic/doctor');
            exit;
        }
        
        $medicines = AllFetch('SELECT id, name FROM medicines ORDER BY name');
        $services = AllFetch('SELECT id, name, price FROM services ORDER BY name');
        
        $headerData = PartialController::getHeaderData();
        
        require_once __DIR__ . '/../views/doctor/prescribe.php';
    }
    

    public function savePrescription()
    {
        $this->checkDoctor();
        header('Content-Type: application/json');
        
        $rawInput = file_get_contents('php://input');
        error_log('=== savePrescription raw: ' . $rawInput);
        
        $input = json_decode($rawInput, true);
        
        if (!$input) {
            echo json_encode(['success' => false, 'message' => 'Нет данных для сохранения']);
            exit;
        }

        if (empty($input['pet_id'])) {
            echo json_encode(['success' => false, 'message' => 'Не указан питомец']);
            exit;
        }
        if (empty($input['appointment_id'])) {
            echo json_encode(['success' => false, 'message' => 'Не указан приём']);
            exit;
        }
        if (empty($input['scheduled_datetime'])) {
            echo json_encode(['success' => false, 'message' => 'Не указана дата и время']);
            exit;
        }
        
        $reminderModel = new Reminder();
        
        $data = [
            'pet_id' => $input['pet_id'],
            'appointment_id' => $input['appointment_id'],
            'medicine_id' => $input['item_id'] ?? null,
            'type' => $input['type'] ?? 'medicine',
            'scheduled_datetime' => $input['scheduled_datetime'],
            'notes' => $input['notes'] ?? null
        ];
        
        $result = $reminderModel->create($data);
        
        if ($result) {
            echo json_encode(['success' => true, 'id' => $result]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Ошибка при сохранении в базу данных']);
        }
        exit;
    }
    
    public function getPrescriptions()
    {
        $this->checkDoctor();
        header('Content-Type: application/json');
        
        $appointmentId = (int)($_GET['appointment_id'] ?? 0);
        $reminderModel = new Reminder();
        
        $prescriptions = $reminderModel->getByAppointment($appointmentId);
        
        echo json_encode(['success' => true, 'data' => $prescriptions]);
        exit;
    }
    
    public function updatePrescriptionStatus()
    {
        $this->checkDoctor();
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $reminderModel = new Reminder();
        
        $result = $reminderModel->updateStatus($input['id'], $input['is_taken']);
        
        echo json_encode($result);
        exit;
    }
    
    public function deletePrescription()
    {
        $this->checkDoctor();
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $reminderModel = new Reminder();
        
        $result = $reminderModel->delete($input['id']);
        
        echo json_encode($result);
        exit;
    }
}