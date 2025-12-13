<?php
// controllers/userController.php
session_start();
require_once __DIR__ . '/../models/UserModel.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // دالة لجلب كل المستخدمين من الموديل
    public function getAllUsers(): array
    {
        return $this->userModel->getAllUsers();
    }

    // Handle admin update action
    public function handleUpdate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Invalid request method';
            header('Location: ../views/dashboard.php');
            exit;
        }

        // Only admins can update
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = 'Unauthorized';
            header('Location: ../views/login.php');
            exit;
        }

        $userId = (int) ($_POST['user_id'] ?? 0);
        $fullName = trim($_POST['full_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $country = trim($_POST['country'] ?? '');

        if ($userId <= 0 || $fullName === '') {
            $_SESSION['error'] = 'Invalid input';
            header('Location: ../views/dashboard.php');
            exit;
        }

        $ok = $this->userModel->updateUser($userId, $fullName, $phone, $address, $country);
        if ($ok) {
            $_SESSION['success'] = 'User updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update user';
        }
        header('Location: ../views/dashboard.php');
        exit;
    }

    // Handle admin delete action
    public function handleDelete()
    {
        // Only admins can delete
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = 'Unauthorized';
            header('Location: ../views/login.php');
            exit;
        }

        $userId = (int) ($_GET['user_id'] ?? 0);
        if ($userId <= 0) {
            $_SESSION['error'] = 'Invalid user id';
            header('Location: ../views/dashboard.php');
            exit;
        }

        if ($this->userModel->deleteUser($userId)) {
            $_SESSION['success'] = "User #{$userId} deleted.";
        } else {
            $_SESSION['error'] = "Failed to delete user #{$userId}.";
        }
        header('Location: ../views/dashboard.php');
        exit;
    }
}

// Handle routing based on action parameter
$action = $_REQUEST['action'] ?? '';

if ($action === 'update') {
    $controller = new UserController();
    $controller->handleUpdate();
} elseif ($action === 'delete') {
    $controller = new UserController();
    $controller->handleDelete();
} else {
    $_SESSION['error'] = 'Unknown action';
    header('Location: ../views/dashboard.php');
    exit;
}
?>