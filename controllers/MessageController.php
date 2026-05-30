<?php
// controllers/MessageController.php

require_once __DIR__ . '/../models/Message.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/PartialController.php';

class MessageController
{
    private $messageModel;

    public function __construct()
    {
        $this->messageModel = new Message();
    }

    public function contactForm()
    {
        $headerData = PartialController::getHeaderData();
        $success = $_SESSION['contact_success'] ?? null;
        $error = $_SESSION['contact_error'] ?? null;
        unset($_SESSION['contact_success'], $_SESSION['contact_error']);

        $userData = [];
        if (isset($_SESSION['userauth']) && $_SESSION['userauth'] === true) {
            $userModel = new User();
            $user = $userModel->findById($_SESSION['userID']);
            $userData = [
                'name' => $user['firstName'] . ' ' . ($user['secondName'] ?? ''),
                'email' => $user['email'],
                'phone' => $user['phone'] ?? ''
            ];
        }

        require_once __DIR__ . '/../views/contact/index.php';
    }


    public function send()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /vetclinic/contact');
            exit;
        }

        $userName = trim($_POST['name'] ?? '');
        $userEmail = trim($_POST['email'] ?? '');
        $userPhone = trim($_POST['phone'] ?? '');
        $message = trim($_POST['message'] ?? '');

        $errors = [];
        if (empty($userName)) {
            $errors[] = 'Введите ваше имя';
        }
        if (empty($userEmail) || !filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Введите корректный email';
        }
        if (empty($message)) {
            $errors[] = 'Введите сообщение';
        }

        if (!empty($errors)) {
            $_SESSION['contact_error'] = implode('<br>', $errors);
            header('Location: /vetclinic/contact');
            exit;
        }

        $data = [
            'user_id' => $_SESSION['userID'] ?? null,
            'user_name' => $userName,      // временно для создания пользователя-гостя
            'user_email' => $userEmail,    // временно для создания пользователя-гостя
            'user_phone' => $userPhone,    // временно для создания пользователя-гостя
            'message' => $message
        ];

        $result = $this->messageModel->create($data);

        if ($result) {
            $_SESSION['contact_success'] = 'Ваше сообщение отправлено! Мы ответим вам в ближайшее время.';
        } else {
            $_SESSION['contact_error'] = 'Ошибка при отправке сообщения. Попробуйте позже.';
        }

        header('Location: /vetclinic/contact');
        exit;
    }

    public function userMessages()
    {
        if (!isset($_SESSION['userauth']) || $_SESSION['userauth'] !== true) {
            header('Location: /vetclinic/auth/login');
            exit;
        }

        $headerData = PartialController::getHeaderData();
        $messages = $this->messageModel->getUserMessages($_SESSION['userID']);
        $replies = $this->messageModel->getUserReplies($_SESSION['userID']);

        require_once __DIR__ . '/../views/user/messages.php';
    }
    

    public function adminMessages()
    {
        $this->checkStaffAccess();

        $messages = $this->messageModel->getAll();
        $newCount = $this->messageModel->getNewCount();
        $headerData = PartialController::getHeaderData();

        require_once __DIR__ . '/../views/admin/messages.php';
    }


    public function getMessage()
    {
        $this->checkStaffAccess();
        header('Content-Type: application/json');

        $id = (int)($_GET['id'] ?? 0);
        $message = $this->messageModel->findById($id);

        if ($message) {
            $this->messageModel->updateStatus($id, 'read');
            echo json_encode(['success' => true, 'data' => $message]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Сообщение не найдено']);
        }
        exit;
    }

    public function reply()
    {
        $this->checkStaffAccess();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $id = (int)($input['id'] ?? 0);
        $reply = trim($input['reply'] ?? '');

        if (empty($reply)) {
            echo json_encode(['success' => false, 'message' => 'Введите ответ']);
            exit;
        }

        $result = $this->messageModel->addReply($id, $reply, $_SESSION['userID']);

        if ($result['success']) {
            echo json_encode(['success' => true, 'message' => 'Ответ сохранён']);
        } else {
            echo json_encode(['success' => false, 'message' => $result['message']]);
        }
        exit;
    }

    private function checkStaffAccess()
    {
        if (!isset($_SESSION['userauth']) || $_SESSION['userauth'] !== true) {
            header('Location: /vetclinic/auth/login');
            exit;
        }

        $userModel = new User();
        $user = $userModel->findById($_SESSION['userID']);
        if (!in_array($user['role_id'], [1, 2])) {
            header('Location: /vetclinic/');
            exit;
        }
    }

    public function delete()
    {
        $this->checkStaffAccess();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $id = (int)($input['id'] ?? 0);

        $result = $this->messageModel->delete($id);
        echo json_encode($result);
        exit;
    }
}