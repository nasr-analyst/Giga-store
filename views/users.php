<?php
// start session and restrict access to admins
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    // not authorized - redirect to home (or show 403)
    header('Location: ../views/index.php');
    exit;
}

// Only include the UserModel, NOT the controller (controller has routing logic)
require_once __DIR__ . '/../models/UserModel.php';

// Create model instance directly
$userModel = new UserModel();
$users = $userModel->getAllUsers();

$userName = $_SESSION['user_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Admin</title>
    <link rel="stylesheet" href="../assets/css/store-style.css">
    <link rel="stylesheet" href="../assets/css/theme.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
    <header class="top-header" style="align-items:flex-start;">
        <div style="display:flex;align-items:center;gap:16px;">
            <div class="logo" style="display:flex;align-items:center;gap:12px;">
                <img src="../assets/images/logo.png" alt="Giga Store" style="height:40px;">
            </div>
        </div>

        <div style="display:flex;align-items:center;gap:12px;">
            <div style="color:var(--muted);font-weight:600;">Welcome, <?= htmlspecialchars($userName) ?>!</div>

            <nav class="nav-links" aria-label="Dashboard navigation">
                <a href="../views/index.php" class="nav-link-btn">Home</a>
                <a href="../views/orders.php" class="nav-link-btn">Orders</a>
                <a href="products.php" class="nav-link-btn">Products</a>
                <a href="../controllers/AuthController.php?action=logout" class="nav-link-btn logout">Logout</a>
            </nav>

            <button id="theme-toggle-btn" class="theme-toggle" aria-pressed="false" title="Toggle theme"></button>

            <div class="cart-icon" onclick="goToCart()" style="cursor:pointer;margin-left:8px;">
                <i class="fa-solid fa-cart-shopping" style="font-size:18px;color:var(--primary-color);"></i>
                <span id="cart-count">0</span>
            </div>
        </div>
    </header>

    <h2>Users Management</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Country</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['full_name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['phone']) ?></td>
                        <td><?= htmlspecialchars($user['address']) ?></td>
                        <td><?= htmlspecialchars($user['country']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <a href="../views/orders.php?user_id=<?= (int) $user['id'] ?>" class="btn btn-show">Show
                                Order</a>
                            <a href="../views/edit_user.php?user_id=<?= (int) $user['id'] ?>" class="btn btn-edit">Edit</a>
                            <a href="../controllers/userController.php?action=delete&user_id=<?= (int) $user['id'] ?>"
                                onclick="return confirm('Delete this user?');" class="btn btn-delete">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Expose current user ID for cart system
        window.CURRENT_USER_ID = <?= (int) $_SESSION['user_id'] ?>;
    </script>

    <script src="../assets/js/theme.js"></script>
    <script src="../assets/js/main.js"></script>
</body>

</html>