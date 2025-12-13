<?php
// OrderController.php — handles order creation and cancellation requests from the frontend.

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/OrderModel.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}

$action = $_POST['action'] ?? '';

// ===== CREATE ORDER =====
if ($action === 'create') {
    // must be logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Please login to place an order']);
        exit;
    }

    $user_id = (int) $_SESSION['user_id'];
    $name = trim($_POST['customer_name'] ?? '');
    $email = trim($_POST['customer_email'] ?? '');
    $phone = trim($_POST['customer_phone'] ?? '');
    $address = trim($_POST['shipping_address'] ?? '');
    $total = (float) ($_POST['total_amount'] ?? 0);
    $cartJson = $_POST['cart_items'] ?? '[]';
    $items = json_decode($cartJson, true);

    if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$phone || !$address || $total <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid shipping or total']);
        exit;
    }
    if (!is_array($items) || !count($items)) {
        echo json_encode(['success' => false, 'message' => 'Cart is empty']);
        exit;
    }

    try {
        $newId = create_order($conn, $user_id, $name, $email, $phone, $address, $total, $items);
        echo json_encode(['success' => true, 'order_id' => $newId]);
    } catch (Throwable $e) {
        error_log('create_order failed: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    }
    exit;
}

// ===== CANCEL ORDER =====
if ($action === 'cancel') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Please login']);
        exit;
    }

    $orderId = (int) ($_POST['order_id'] ?? 0);
    if ($orderId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid order id']);
        exit;
    }

    // Verify the order belongs to this user (or user is admin)
    $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    $userId = (int) $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT user_id FROM Orders WHERE id = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
        exit;
    }
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
    $res = $stmt->get_result();
    $order = $res ? $res->fetch_assoc() : null;
    $stmt->close();

    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }

    // Check authorization
    if (!$isAdmin && (int) $order['user_id'] !== $userId) {
        echo json_encode(['success' => false, 'message' => 'Not authorized']);
        exit;
    }

    // Cancel the order
    if (cancel_order($conn, $orderId)) {
        echo json_encode(['success' => true, 'message' => 'Order cancelled successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to cancel order (may already be shipped)']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);
exit;
?>