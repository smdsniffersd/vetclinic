<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/PartialController.php';

class UserController
{
    private $userModel;
    
    public function __construct()
    {
        $this->userModel = new User();
    }
    
    public function account()
    {
        if (!isset($_SESSION['userauth']) || $_SESSION['userauth'] !== true) {
            header('Location: /vetclinic/auth/login');
            exit;
        }
        
        $userId = $_SESSION['userID'];
        
        $user = $this->userModel->findById($userId);
        $pets = $this->userModel->getPetsWithMedicalInfo($userId);
        $upcomingAppointments = $this->userModel->getUpcomingAppointments($userId);
        $historyAppointments = $this->userModel->getAppointmentHistory($userId, 5);
        
        $headerData = PartialController::getHeaderData();
        
        $success = $_SESSION['profile_success'] ?? null;
        $errors = $_SESSION['profile_errors'] ?? [];
        unset($_SESSION['profile_success'], $_SESSION['profile_errors']);
        
        require_once __DIR__ . '/../views/user/account.php';
    }
    
    public function updateProfile()
    {
        if (!isset($_SESSION['userauth']) || $_SESSION['userauth'] !== true) {
            header('Location: /vetclinic/auth/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /vetclinic/user/account');
            exit;
        }
        
        $data = [
            'firstName' => trim($_POST['firstName'] ?? ''),
            'secondName' => trim($_POST['secondName'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? '')
        ];
        
        $result = $this->userModel->updateProfile($_SESSION['userID'], $data);
        
        if ($result['success']) {
            $_SESSION['profile_success'] = 'Профиль успешно обновлён';
        } else {
            $_SESSION['profile_errors'] = [$result['message']];
        }
        
        header('Location: /vetclinic/user/account');
        exit;
    }
}