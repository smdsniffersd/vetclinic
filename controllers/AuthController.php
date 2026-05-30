<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/PartialController.php';

class AuthController
{
    private $userModel;
    
    public function __construct()
    {
        $this->userModel = new User();
    }
    
    public function loginForm()
    {
        if (isset($_SESSION['userauth']) && $_SESSION['userauth'] === true) {
            header('Location: /vetclinic/user/account');
            exit;
        }
        
        $headerData = PartialController::getHeaderData();
        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);
        
        require_once __DIR__ . '/../views/auth/login.php';
    }
    
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /vetclinic/auth/login');
            exit;
        }
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Валидация
        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = 'Заполните все поля';
            header('Location: /vetclinic/auth/login');
            exit;
        }
        
        $user = $this->userModel->authenticate($email, $password);
        
        if ($user) {
            $_SESSION['userID'] = $user['id'];
            $_SESSION['firstName'] = $user['firstName'];
            $_SESSION['secondName'] = $user['secondName'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['phone'] = $user['phone'];
            $_SESSION['userauth'] = true;

            insertRow('logs', [
                'userID' => $user['id'],
                'event' => 'authorization',
                'time' => date('Y-m-d H:i:s')
            ]);

            switch ($user['role_id']) {
                case 1:
                    header('Location: /vetclinic/admin');
                    break;
                case 2:
                    header('Location: /vetclinic/doctor');
                    break;
                default:
                    header('Location: /vetclinic/user/account');
            }
        } else {
            $_SESSION['login_error'] = 'Неверный email или пароль';
            header('Location: /vetclinic/auth/login');
        }
        exit;
    }
    
    public function registerForm()
    {

        if (isset($_SESSION['userauth']) && $_SESSION['userauth'] === true) {
            header('Location: /vetclinic/user/account');
            exit;
        }
        
        $headerData = PartialController::getHeaderData();
        $errors = $_SESSION['register_errors'] ?? [];
        $oldData = $_SESSION['register_old'] ?? [];
        unset($_SESSION['register_errors'], $_SESSION['register_old']);
        
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /vetclinic/auth/register');
            exit;
        }
        
        $userData = [
            'firstName' => trim($_POST['firstName'] ?? ''),
            'secondName' => trim($_POST['secondName'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? '',
            'role_id' => 4
        ];
        
        $result = $this->userModel->create($userData);
        
        if ($result['success']) {
            $_SESSION['userID'] = $result['user_id'];
            $_SESSION['firstName'] = $userData['firstName'];
            $_SESSION['secondName'] = $userData['secondName'];
            $_SESSION['email'] = $userData['email'];
            $_SESSION['phone'] = $userData['phone'];
            $_SESSION['userauth'] = true;
            
            header('Location: /vetclinic/user/account');
        } else {
            $_SESSION['register_errors'] = $result['errors'];
            $_SESSION['register_old'] = $userData;
            header('Location: /vetclinic/auth/register');
        }
        exit;
    }
    
    public function logout()
    {
        if (isset($_SESSION['userID'])) {
            insertRow('logs', [
                'userID' => $_SESSION['userID'],
                'event' => 'logout',
                'time' => date('Y-m-d H:i:s')
            ]);
        }
        
        $_SESSION = [];
        
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path']);
        }
        
        session_destroy();
        header('Location: /vetclinic/');
        exit;
    }
}