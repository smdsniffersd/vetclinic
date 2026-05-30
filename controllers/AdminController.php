<?php

require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/PartialController.php';

class AdminController
{
    private $adminModel;
    private $userModel;

    public function __construct()
    {
        $this->adminModel = new Admin();
        $this->userModel = new User();
    }


    private function checkAdmin()
    {
        if (!isset($_SESSION['userauth']) || $_SESSION['userauth'] !== true) {
            header('Location: /vetclinic/auth/login');
            exit;
        }

        $user = $this->userModel->findById($_SESSION['userID']);
        if (!$user || $user['role_id'] != 1) {
            header('Location: /vetclinic/');
            exit;
        }
    }
    public function dashboard()
    {
        $this->checkAdmin();

        $stats = $this->adminModel->getStats();
        $headerData = PartialController::getHeaderData();

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function users()
    {
        $this->checkAdmin();

        $users = $this->adminModel->getAllUsers();
        $headerData = PartialController::getHeaderData();

        require_once __DIR__ . '/../views/admin/users.php';
    }
    public function pets()
    {
        $this->checkAdmin();

        $pets = $this->adminModel->getAllPets();
        $headerData = PartialController::getHeaderData();

        require_once __DIR__ . '/../views/admin/pets.php';
    }
    public function appointments()
    {
        $this->checkAdmin();

        $appointments = $this->adminModel->getAllAppointments();
        $headerData = PartialController::getHeaderData();

        require_once __DIR__ . '/../views/admin/appointments.php';
    }

    public function personal()
    {
        $this->checkAdmin();

        $personal = $this->adminModel->getAllPersonal();
        $headerData = PartialController::getHeaderData();

        require_once __DIR__ . '/../views/admin/personal.php';
    }

    public function services()
    {
        $this->checkAdmin();

        $services = $this->adminModel->getAllServices();
        $headerData = PartialController::getHeaderData();

        require_once __DIR__ . '/../views/admin/services.php';
    }

    public function changeRole()
    {
        $this->checkAdmin();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $userId = (int)($input['user_id'] ?? 0);
        $roleId = (int)($input['role_id'] ?? 0);

        $result = $this->adminModel->changeUserRole($userId, $roleId);
        echo json_encode($result);
        exit;
    }

    public function deleteUser()
    {
        $this->checkAdmin();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $userId = (int)($input['id'] ?? 0);

        $result = deleteRow('users', 'id', $userId);
        echo json_encode($result);
        exit;
    }

    public function deleteAppointment()
    {
        $this->checkAdmin();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $id = (int)($input['id'] ?? 0);

        $result = deleteRow('appointments', 'id', $id);
        echo json_encode($result);
        exit;
    }

    public function updateAppointmentStatus()
    {
        $this->checkAdmin();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $id = (int)($input['id'] ?? 0);
        $status = $input['status'] ?? 'active';

        $result = update('appointments', ['status' => $status], 'id', $id);
        echo json_encode($result);
        exit;
    }

    public function deletePet()
    {
        $this->checkAdmin();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $result = deleteRow('pets', 'id', $input['id']);
        echo json_encode($result);
    }

    public function deletePersonal()
    {
        $this->checkAdmin();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $result = deleteRow('personal', 'id', $input['id']);
        echo json_encode($result);
    }
    public function addServiceForm()
    {
        $this->checkAdmin();
        $headerData = PartialController::getHeaderData();
        require_once __DIR__ . '/../views/admin/services-add.php';
    }
    public function editServiceForm($id)
    {
        $this->checkAdmin();
        $headerData = PartialController::getHeaderData();
        $service = OneFetch('SELECT * FROM services WHERE id = ?', [$id]);

        if (!$service) {
            header('Location: /vetclinic/admin/services');
            exit;
        }

        require_once __DIR__ . '/../views/admin/services-edit.php';
    }

    public function getService()
    {
        $this->checkAdmin();
        header('Content-Type: application/json');

        $id = (int)($_GET['id'] ?? 0);
        $service = OneFetch('SELECT * FROM services WHERE id = ?', [$id]);
        echo json_encode(['success' => true, 'service' => $service]);
    }

    public function addService()
    {
        $this->checkAdmin();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);

        $data = [
            'name' => $input['name'] ?? '',
            'price' => $input['price'] ?? 0,
            'doctor_type_id' => $input['doctor_type_id'] ?? null
        ];

        $result = insertRow('services', $data);
        echo json_encode(['success' => (bool)$result, 'id' => $result]);
    }


    public function updateService()
    {
        $this->checkAdmin();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);

        $data = [
            'name' => $input['name'] ?? '',
            'price' => $input['price'] ?? 0,
            'doctor_type_id' => $input['doctor_type_id'] ?? null
        ];

        $result = update('services', $data, 'id', $input['id']);
        echo json_encode($result);
    }

    public function deleteService()
    {
        $this->checkAdmin();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $result = deleteRow('services', 'id', $input['id']);
        echo json_encode($result);
    }

    public function addPersonalForm()
    {
        $this->checkAdmin();
        $headerData = PartialController::getHeaderData();
        require_once __DIR__ . '/../views/admin/personal-add.php';
    }

    public function editPersonalForm($id)
    {
        $this->checkAdmin();
        $headerData = PartialController::getHeaderData();
        $personal = OneFetch('SELECT * FROM personal WHERE id = ?', [$id]);

        if (!$personal) {
            header('Location: /vetclinic/admin/personal');
            exit;
        }

        require_once __DIR__ . '/../views/admin/personal-edit.php';
    }

    public function addPersonal()
    {
        $this->checkAdmin();
        header('Content-Type: application/json');

        $data = [
            'first_name' => $_POST['first_name'] ?? '',
            'second_name' => $_POST['second_name'] ?? '',
            'phone_number' => $_POST['phone_number'] ?? '',
            'email' => $_POST['email'] ?? '',
            'addres' => $_POST['address'] ?? '',
            'profession_id' => $_POST['profession_id'] ?? null,
            'role_id' => $_POST['role_id'] ?? null,
            'birthday' => $_POST['birthday'] ?? null,
            'experience_work' => $_POST['experience_work'] ?? 0,
            'pass' => $_POST['pass'] ?? password_hash('123456', PASSWORD_DEFAULT)
        ];

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../public/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = 'personal_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $filename);
            $data['photo'] = 'uploads/' . $filename;
        }

        $result = insertRow('personal', $data);
        echo json_encode(['success' => (bool)$result, 'id' => $result]);
    }

    public function updatePersonal()
    {
        $this->checkAdmin();
        header('Content-Type: application/json');

        $id = $_POST['id'] ?? 0;
        $data = [
            'first_name' => $_POST['first_name'] ?? '',
            'second_name' => $_POST['second_name'] ?? '',
            'phone_number' => $_POST['phone_number'] ?? '',
            'email' => $_POST['email'] ?? '',
            'addres' => $_POST['address'] ?? '',
            'profession_id' => $_POST['profession_id'] ?? null,
            'role_id' => $_POST['role_id'] ?? null,
            'birthday' => $_POST['birthday'] ?? null,
            'experience_work' => $_POST['experience_work'] ?? 0
        ];

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../public/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = 'personal_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $filename);
            $data['photo'] = 'uploads/' . $filename;
        }

        $result = update('personal', $data, 'id', $id);
        echo json_encode($result);
    }


    public function editUserForm($id)
    {
        $this->checkAdmin();
        $headerData = PartialController::getHeaderData();

        $user = $this->userModel->findById($id);

        if (!$user) {
            header('Location: /vetclinic/admin/users');
            exit;
        }

        $roles = AllFetch('SELECT * FROM roles ORDER BY id');

        require_once __DIR__ . '/../views/admin/user-edit.php';
    }

    public function updateUser()
    {
        $this->checkAdmin();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $id = (int)($input['id'] ?? 0);

        $data = [
            'firstName' => $input['first_name'] ?? '',
            'secondName' => $input['second_name'] ?? '',
            'email' => $input['email'] ?? '',
            'phone' => $input['phone'] ?? '',
            'role_id' => (int)($input['role_id'] ?? 4),
            'gender' => $input['gender'] ?? null,
            'birthday' => $input['birthday'] ?? null
        ];

        if (!empty($input['password'])) {
            $data['pass'] = password_hash($input['password'], PASSWORD_DEFAULT);
        }

        $result = update('users', $data, 'id', $id);
        echo json_encode(['success' => (bool)$result]);
        exit;
    }


    public function editPetForm($id)
    {
        $this->checkAdmin();
        $headerData = PartialController::getHeaderData();

        $pet = OneFetch('SELECT * FROM pets WHERE id = ?', [$id]);

        if (!$pet) {
            header('Location: /vetclinic/admin/pets');
            exit;
        }

        $owners = AllFetch('SELECT id, firstName, secondName, email FROM users ORDER BY firstName');

        require_once __DIR__ . '/../views/admin/pet-edit.php';
    }

    public function updatePet()
    {
        $this->checkAdmin();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $id = (int)($input['id'] ?? 0);

        $data = [
            'name' => $input['name'] ?? '',
            'view' => $input['view'] ?? '',
            'Breed' => $input['breed'] ?? '',
            'Age' => (int)($input['age'] ?? 0),
            'weight' => (float)($input['weight'] ?? 0),
            'owner_id' => (int)($input['owner_id'] ?? 0)
        ];

        $result = update('pets', $data, 'id', $id);
        echo json_encode(['success' => (bool)$result]);
        exit;
    }


    public function editAppointmentForm($id)
    {
        $this->checkAdmin();
        $headerData = PartialController::getHeaderData();

        $appointment = OneFetch('SELECT * FROM appointments WHERE id = ?', [$id]);

        if (!$appointment) {
            header('Location: /vetclinic/admin/appointments');
            exit;
        }

        $users = AllFetch('SELECT id, firstName, secondName, email FROM users ORDER BY firstName');

        $pets = AllFetch('SELECT p.*, u.firstName as owner_name 
                      FROM pets p 
                      LEFT JOIN users u ON u.id = p.owner_id 
                      ORDER BY p.name');

        $services = $this->adminModel->getAllServices();

        $doctors = AllFetch('SELECT id, first_name, second_name, profession_id 
                         FROM personal 
                         WHERE role_id = 2 OR profession_id = 1
                         ORDER BY first_name');

        require_once __DIR__ . '/../views/admin/appointment-edit.php';
    }

    public function updateAppointment()
    {
        $this->checkAdmin();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $id = (int)($input['id'] ?? 0);

        $data = [
            'user_id' => (int)($input['user_id'] ?? 0),
            'pet_id' => (int)($input['pet_id'] ?? 0),
            'service_id' => (int)($input['service_id'] ?? 0),
            'doctor_id' => !empty($input['doctor_id']) ? (int)$input['doctor_id'] : null,
            'date' => $input['date'] ?? null,
            'time' => $input['time'] ?? null,
            'status' => $input['status'] ?? 'active'
        ];

        $result = update('appointments', $data, 'id', $id);
        echo json_encode(['success' => (bool)$result]);
        exit;
    }

    public function getPetsByUser()
    {
        $this->checkAdmin();
        header('Content-Type: application/json');

        $userId = (int)($_GET['user_id'] ?? 0);
        $pets = AllFetch('SELECT id, name FROM pets WHERE owner_id = ?', [$userId]);

        echo json_encode(['success' => true, 'pets' => $pets]);
        exit;
    }
}
