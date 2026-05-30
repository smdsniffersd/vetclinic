<?php

require_once __DIR__ . '/../models/Appointment.php';
require_once __DIR__ . '/../models/Pet.php';
require_once __DIR__ . '/../models/Service.php';
require_once __DIR__ . '/../models/Personal.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/PartialController.php';

class AppointmentController
{
    private $appointmentModel;
    private $petModel;
    private $serviceModel;
    private $personalModel;
    private $userModel;

    public function __construct()
    {
        $this->appointmentModel = new Appointment();
        $this->petModel = new Pet();
        $this->serviceModel = new Service();
        $this->personalModel = new Personal();
        $this->userModel = new User();
    }

    public function bookingForm()
    {
        $headerData = PartialController::getHeaderData();

        $services = $this->serviceModel->findAll();
        $doctors = $this->personalModel->getDoctors();

        $userPets = [];
        if (isset($_SESSION['userauth']) && $_SESSION['userauth'] === true) {
            $userPets = $this->petModel->getByOwnerId($_SESSION['userID']);
        }

        $error = $_SESSION['booking_error'] ?? null;
        unset($_SESSION['booking_error']);

        require_once __DIR__ . '/../views/booking/appointment.php';
    }

    public function book()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Неверный метод запроса']);
            exit;
        }

        $petId = (int)($_POST['pet_id'] ?? 0);
        $serviceId = (int)($_POST['service_id'] ?? 0);
        $doctorId = (int)($_POST['doctor_id'] ?? 0);
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        $specificCondition = trim($_POST['specific_condition'] ?? '');
        $newPetName = trim($_POST['new_pet_name'] ?? '');
        $newPetType = $_POST['new_pet_type'] ?? '';
        $newPetBreed = trim($_POST['new_pet_breed'] ?? '');
        $newPetAge = (int)($_POST['new_pet_age'] ?? 0);
        $newPetWeight = (float)($_POST['new_pet_weight'] ?? 0);
        if (empty($date) || empty($time)) {
            echo json_encode(['success' => false, 'message' => 'Выберите дату и время']);
            exit;
        }

        if ($serviceId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Выберите услугу']);
            exit;
        }

        if ($doctorId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Выберите врача']);
            exit;
        }

        if ($date < date('Y-m-d')) {
            echo json_encode(['success' => false, 'message' => 'Нельзя записаться на прошедшую дату']);
            exit;
        }

        $existingAppointment = OneFetch(
            'SELECT id FROM appointments WHERE date = ? AND time = ? AND status != "cancelled"',
            [$date, $time]
        );

        if ($existingAppointment) {
            echo json_encode(['success' => false, 'message' => 'Это время уже занято. Пожалуйста, выберите другое.']);
            exit;
        }
        $userId = $this->handleUser($_POST);

        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Ошибка при обработке пользователя']);
            exit;
        }
        if ($petId > 0) {
            $finalPetId = $petId;
        } elseif (!empty($newPetName) && !empty($newPetType)) {
            $petData = [
                'owner_id' => $userId,
                'name' => $newPetName,
                'view' => $newPetType,
                'breed' => $newPetBreed,
                'age' => $newPetAge,
                'weight' => $newPetWeight,
                'special_marks' => ''
            ];
            $finalPetId = $this->petModel->create($petData);

            if (!$finalPetId) {
                echo json_encode(['success' => false, 'message' => 'Ошибка при создании питомца']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Выберите питомца или добавьте нового']);
            exit;
        }

        $existingAppointment2 = OneFetch(
            'SELECT id FROM appointments WHERE date = ? AND time = ? AND status != "cancelled"',
            [$date, $time]
        );

        if ($existingAppointment2) {
            echo json_encode(['success' => false, 'message' => 'Это время уже занято. Пожалуйста, выберите другое.']);
            exit;
        }

        $appointmentData = [
            'user_id' => $userId,
            'pet_id' => $finalPetId,
            'service_id' => $serviceId,
            'doctor_id' => $doctorId,
            'date' => $date,
            'time' => $time,
            'status' => 'active',
            'specific_condition' => $specificCondition
        ];

        $result = $this->appointmentModel->create($appointmentData);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Запись успешно создана!', 'redirect' => '/vetclinic/user/account']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Ошибка при создании записи']);
        }
        exit;
    }

    public function getFreeSlots()
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $date = $input['date'] ?? null;

        if (!$date) {
            echo json_encode(['success' => false, 'message' => 'Дата не указана']);
            exit;
        }

        $allTimes = ['10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00'];
        $today = date('Y-m-d');

        try {
            $appointments = $this->appointmentModel->getByDate($date);
            $bookedTimes = [];

            foreach ($appointments as $apt) {
                $aptTime = date('H:i', strtotime($apt['time']));
                $bookedTimes[] = $aptTime;
            }

            $currentHour = (int)date('H');
            $currentMinute = (int)date('i');

            if ($date == $today) {
                $availableTimes = [];
                foreach ($allTimes as $time) {
                    $timeHour = (int)substr($time, 0, 2);
                    $timeMinute = (int)substr($time, 3, 2);

                    if ($timeHour > $currentHour || ($timeHour == $currentHour && $timeMinute > $currentMinute)) {
                        if (!in_array($time, $bookedTimes)) {
                            $availableTimes[] = $time;
                        }
                    }
                }
                $freeTimes = $availableTimes;
            } elseif ($date < $today) {
                $freeTimes = [];
            } else {
                $freeTimes = array_diff($allTimes, $bookedTimes);
            }

            echo json_encode([
                'success' => true,
                'date' => $date,
                'book_times' => array_values($bookedTimes),
                'free_times' => array_values($freeTimes)
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
        exit;
    }

    private function handleUser($postData)
    {
        if (isset($_SESSION['userauth']) && $_SESSION['userauth'] === true) {
            return $_SESSION['userID'];
        }

        $email = trim($postData['email'] ?? '');
        if (empty($email)) {
            return null;
        }

        $existingUser = $this->userModel->findByEmail($email);

        if ($existingUser) {
            return $existingUser['id'];
        }

        $userData = [
            'firstName' => trim($postData['firstName'] ?? 'Гость'),
            'secondName' => trim($postData['secondName'] ?? ''),
            'email' => $email,
            'phone' => trim($postData['phone'] ?? ''),
            'address' => trim($postData['address'] ?? ''),
            'password' => bin2hex(random_bytes(8)),
            'role_id' => 4
        ];

        $result = $this->userModel->create($userData);
        return $result['success'] ?? false ? $result['user_id'] : null;
    }
}
