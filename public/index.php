<?php

session_start();

require_once __DIR__ . '/../config.php';

$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

$projectFolder = 'vetclinic';
$path = str_replace('/' . $projectFolder, '', $path);
$path = str_replace($projectFolder, '', $path);
$path = str_replace('public/index.php', '', $path);
$path = trim($path, '/');

$method = $_SERVER['REQUEST_METHOD'];

// Редактирование персонала
if (preg_match('#^admin/personal/edit/(\d+)$#', $path, $matches)) {
    $id = (int)$matches[1];
    require_once __DIR__ . '/../controllers/AdminController.php';
    $controller = new AdminController();
    $controller->editPersonalForm($id);
    exit;
}

// Редактирование пользователей (НОВЫЙ МАРШРУТ)
if (preg_match('#^admin/users/edit/(\d+)$#', $path, $matches)) {
    $id = (int)$matches[1];
    require_once __DIR__ . '/../controllers/AdminController.php';
    $controller = new AdminController();
    $controller->editUserForm($id);
    exit;
}

// Редактирование питомцев (НОВЫЙ МАРШРУТ)
if (preg_match('#^admin/pets/edit/(\d+)$#', $path, $matches)) {
    $id = (int)$matches[1];
    require_once __DIR__ . '/../controllers/AdminController.php';
    $controller = new AdminController();
    $controller->editPetForm($id);
    exit;
}

// Редактирование записей (НОВЫЙ МАРШРУТ)
if (preg_match('#^admin/appointments/edit/(\d+)$#', $path, $matches)) {
    $id = (int)$matches[1];
    require_once __DIR__ . '/../controllers/AdminController.php';
    $controller = new AdminController();
    $controller->editAppointmentForm($id);
    exit;
}

// Назначение лечения доктором
if (preg_match('#^doctor/prescribe/(\d+)$#', $path, $matches)) {
    $id = (int)$matches[1];
    require_once __DIR__ . '/../controllers/DoctorController.php';
    $controller = new DoctorController();
    $controller->prescribeForm($id);
    exit;
}

// Редактирование услуг
if (preg_match('#^admin/services/edit/(\d+)$#', $path, $matches)) {
    $id = (int)$matches[1];
    require_once __DIR__ . '/../controllers/AdminController.php';
    $controller = new AdminController();
    $controller->editServiceForm($id);
    exit;
}

switch ($path) {

    case '':
    case 'home':
    case 'index':
        require_once __DIR__ . '/../controllers/PageController.php';
        $controller = new PageController();
        $controller->home();
        break;

    case 'about':
        require_once __DIR__ . '/../controllers/PageController.php';
        $controller = new PageController();
        $controller->about();
        break;

    case 'services':
        require_once __DIR__ . '/../controllers/PageController.php';
        $controller = new PageController();
        $controller->services();
        break;

    case 'user/account':
        require_once __DIR__ . '/../controllers/UserController.php';
        $controller = new UserController();
        $controller->account();
        break;

    case 'user/update':
        require_once __DIR__ . '/../controllers/UserController.php';
        $controller = new UserController();
        $controller->updateProfile();
        break;

    case 'user/messages':
        require_once __DIR__ . '/../controllers/MessageController.php';
        $controller = new MessageController();
        $controller->userMessages();
        break;

    case 'auth/login':
        if ($method === 'POST') {
            require_once __DIR__ . '/../controllers/AuthController.php';
            $controller = new AuthController();
            $controller->login();
        } else {
            require_once __DIR__ . '/../controllers/AuthController.php';
            $controller = new AuthController();
            $controller->loginForm();
        }
        break;

    case 'auth/register':
        if ($method === 'POST') {
            require_once __DIR__ . '/../controllers/AuthController.php';
            $controller = new AuthController();
            $controller->register();
        } else {
            require_once __DIR__ . '/../controllers/AuthController.php';
            $controller = new AuthController();
            $controller->registerForm();
        }
        break;

    case 'auth/logout':
        require_once __DIR__ . '/../controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'booking':
        require_once __DIR__ . '/../controllers/AppointmentController.php';
        $controller = new AppointmentController();
        $controller->bookingForm();
        break;

    case 'contact':
        require_once __DIR__ . '/../controllers/MessageController.php';
        $controller = new MessageController();
        $controller->contactForm();
        break;

    case 'admin/messages':
        require_once __DIR__ . '/../controllers/MessageController.php';
        $controller = new MessageController();
        $controller->adminMessages();
        break;

    case 'admin':
    case 'admin/dashboard':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->dashboard();
        break;

    case 'admin/users':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->users();
        break;

    case 'admin/pets':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->pets();
        break;

    case 'admin/appointments':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->appointments();
        break;

    case 'admin/personal':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->personal();
        break;

    case 'admin/services':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->services();
        break;

    case 'admin/personal/add':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->addPersonalForm();
        break;

    case 'admin/services/add':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->addServiceForm();
        break;

    case 'doctor':
    case 'doctor/dashboard':
        require_once __DIR__ . '/../controllers/DoctorController.php';
        $controller = new DoctorController();
        $controller->dashboard();
        break;

    // === API МАРШРУТЫ ДЛЯ АДМИНИСТРАТОРА ===

    case 'api/appointments':
        require_once __DIR__ . '/../controllers/AppointmentController.php';
        $controller = new AppointmentController();

        if ($method === 'POST') {
            $controller->book();
        } elseif ($method === 'DELETE') {
            $controller->cancel();
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Метод не разрешён']);
        }
        break;

    case 'api/calendar':
        require_once __DIR__ . '/../controllers/AppointmentController.php';
        $controller = new AppointmentController();
        $controller->getFreeSlots();
        break;

    case 'api/contact/send':
        require_once __DIR__ . '/../controllers/MessageController.php';
        $controller = new MessageController();
        $controller->send();
        break;

    case 'api/message/get':
        require_once __DIR__ . '/../controllers/MessageController.php';
        $controller = new MessageController();
        $controller->getMessage();
        break;

    case 'api/message/reply':
        require_once __DIR__ . '/../controllers/MessageController.php';
        $controller = new MessageController();
        $controller->reply();
        break;

    case 'api/message/delete':
        require_once __DIR__ . '/../controllers/MessageController.php';
        $controller = new MessageController();
        $controller->delete();
        break;

    case 'api/admin/change-role':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->changeRole();
        break;

    case 'api/admin/delete-user':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->deleteUser();
        break;

    case 'api/admin/delete-appointment':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->deleteAppointment();
        break;

    case 'api/admin/update-status':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->updateAppointmentStatus();
        break;

    case 'api/admin/table':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->getTableData();
        break;

    case 'api/admin/delete':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->deleteRow();
        break;

    case 'api/doctor/status':
        require_once __DIR__ . '/../controllers/DoctorController.php';
        $controller = new DoctorController();
        $controller->updateAppointmentStatus();
        break;

    case 'api/admin/add-service':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->addService();
        break;

    case 'api/admin/update-service':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->updateService();
        break;

    case 'api/admin/delete-service':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->deleteService();
        break;

    case 'api/admin/get-service':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->getService();
        break;

    case 'api/admin/delete-pet':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->deletePet();
        break;

    case 'api/admin/delete-personal':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->deletePersonal();
        break;

    case 'api/admin/add-personal':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->addPersonal();
        break;

    case 'api/admin/update-personal':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->updatePersonal();
        break;

    // НОВЫЕ API МАРШРУТЫ ДЛЯ РЕДАКТИРОВАНИЯ
    case 'api/admin/update-user':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->updateUser();
        break;

    case 'api/admin/update-pet':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->updatePet();
        break;

    case 'api/admin/update-appointment':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->updateAppointment();
        break;

    case 'api/admin/get-pets-by-user':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->getPetsByUser();
        break;

    // === ДОКТОР МАРШРУТЫ ===
    case 'api/doctor/appointment-details':
        require_once __DIR__ . '/../controllers/DoctorController.php';
        $controller = new DoctorController();
        $controller->getAppointmentDetails();
        break;

    case 'api/doctor/save-prescription':
        require_once __DIR__ . '/../controllers/DoctorController.php';
        $controller = new DoctorController();
        $controller->savePrescription();
        break;

    case 'api/doctor/get-prescriptions':
        require_once __DIR__ . '/../controllers/DoctorController.php';
        $controller = new DoctorController();
        $controller->getPrescriptions();
        break;

    case 'api/doctor/update-prescription-status':
        require_once __DIR__ . '/../controllers/DoctorController.php';
        $controller = new DoctorController();
        $controller->updatePrescriptionStatus();
        break;

    case 'api/doctor/delete-prescription':
        require_once __DIR__ . '/../controllers/DoctorController.php';
        $controller = new DoctorController();
        $controller->deletePrescription();
        break;

    // === СТРАНИЦЫ ===
    case 'pet-health-programs':
        require_once __DIR__ . '/../controllers/PageController.php';
        $controller = new PageController();
        $controller->petHealthPrograms();
        break;

    // === РЕДИРЕКТЫ ДЛЯ СТАРЫХ ФАЙЛОВ ===
    case 'user_account.php':
        header('Location: /vetclinic/user/account');
        exit;

    case 'adminpanel.php':
        header('Location: /vetclinic/admin');
        exit;

    case 'doctorpanel.php':
        header('Location: /vetclinic/doctor');
        exit;

    case 'Appoinment_Booking.php':
        header('Location: /vetclinic/booking');
        exit;

    case 'contact_us.php':
        header('Location: /vetclinic/contact');
        exit;

    default:
        http_response_code(404);
        echo "404 — Страница не найдена. Path: '" . $path . "'";
        break;
}