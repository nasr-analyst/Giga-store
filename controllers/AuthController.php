<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Handle Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Please fill all fields';
        header('Location: ../views/login.php');
        exit;
    }

    $stmt = $conn->prepare("SELECT id, full_name, email, password_hash, role FROM Users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Login successful
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

    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['error'] = 'Please fill all fields';
        header('Location: ../views/register.php');
        exit;
    }

    if ($password !== $confirmPassword) {
        $_SESSION['error'] = 'Passwords do not match';
        header('Location: ../views/register.php');
        exit;
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM Users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['error'] = 'Email already registered';
        header('Location: ../views/register.php');
        exit;
    }
    $stmt->close();

    // Hash password and insert new user
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO Users (full_name, email, password_hash, role) VALUES (?, ?, ?, 'customer')");
    $stmt->bind_param('sss', $name, $email, $passwordHash);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Registration successful! Please login.';
        header('Location: ../views/login.php');
    } else {
        $_SESSION['error'] = 'Registration failed. Please try again.';
        header('Location: ../views/register.php');
    }
    $stmt->close();
    exit;
}

// Handle Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: ../views/login.php');
    exit;
}
?>