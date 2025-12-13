<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../models/UserModel.php';
$userModel = new UserModel();

$userId = (int) ($_GET['user_id'] ?? 0);
if ($userId <= 0) {
    $_SESSION['error'] = 'Invalid user id';
    header('Location: dashboard.php');
    exit;
}

$user = $userModel->getUserById($userId);
if (!$user) {
    $_SESSION['error'] = 'User not found';
    header('Location: dashboard.php');
    exit;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Edit User #<?= htmlspecialchars($userId) ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/theme.css">
    <link rel="stylesheet" href="../assets/css/pages.css">
</head>

<body>
    <header class="top-header">
        <div class="logo"><img src="../assets/images/logo.png" alt="Giga"></div>
        <nav class="nav-links"><a href="dashboard.php">Back to dashboard</a></nav>
    </header>

    <main class="edit-card">
        <h2>Edit User #<?= htmlspecialchars($userId) ?></h2>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="error-box">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="success-box">' . htmlspecialchars($_SESSION['success']) . '</div>';
            unset($_SESSION['success']);
        }
        ?>

        <form action="../controllers/userController.php?action=update" method="post">
            <input type="hidden" name="user_id" value="<?= (int) $userId ?>">
            <div class="form-row">
                <label>Full name</label><br>
                <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" required>
            </div>
            <div class="form-row">
                <label>Phone</label><br>
                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
            </div>
            <div class="form-row">
                <label>Address</label><br>
                <input type="text" name="address" value="<?= htmlspecialchars($user['address'] ?? '') ?>">
            </div>
            <div class="form-row">
                <label>Country</label><br>
                <input type="text" name="country" value="<?= htmlspecialchars($user['country'] ?? '') ?>">
            </div>

            <div class="edit-actions">
                <button type="submit" class="btn btn-edit">Save</button>
                <a href="../controllers/userController.php?action=delete&user_id=<?= (int) $userId ?>"
                    onclick="return confirm('Delete user?');" class="btn btn-delete">Delete</a>
            </div>
        </form>
    </main>

    <script src="../assets/js/theme.js"></script>
</body>

</html>