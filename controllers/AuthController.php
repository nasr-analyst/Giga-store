<?php
session_start();

require_once __DIR__ . '/../models/UserModel.php';

$userModel = new UserModel();

// Handle Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Please fill all fields';
        header('Location: ../views/login.php');
        exit;
    }

    // Use model to authenticate
    $user = $userModel->loginUser($email, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];

        header('Location: ../views/index.php');
        exit;
    } else {
        $_SESSION['error'] = 'Invalid email or password';
        header('Location: ../views/login.php');
        exit;
    }
}

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm-password'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $country = trim($_POST['country'] ?? '');

    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['error'] = 'Please fill all required fields';
        header('Location: ../views/register.php');
        exit;
    }

    if ($password !== $confirmPassword) {
        $_SESSION['error'] = 'Passwords do not match';
        header('Location: ../views/register.php');
        exit;
    }

    // Check email via model
    $existing = $userModel->getUserByEmail($email);
    if ($existing) {
        $_SESSION['error'] = 'Email already registered';
        header('Location: ../views/register.php');
        exit;
    }

    // Use model to register
    $ok = $userModel->registerUser($name, $email, $password, $phone, $address, $country);
    if ($ok) {
        $_SESSION['success'] = 'Registration successful! Please login.';
        header('Location: ../views/login.php');
        exit;
    } else {
        // Insertion failed (DB constraint or other error)
        $_SESSION['error'] = 'Registration failed. Please try again.';
        header('Location: ../views/register.php');
        exit;
    }
}

// Handle Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: ../views/login.php');
    exit;
}
?>