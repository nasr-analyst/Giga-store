<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/OrderdetailsModel.php';

$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
if (!$isAdmin && !isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

// which user's orders to show
if ($isAdmin && isset($_GET['user_id'])) {
  $userId = (int) $_GET['user_id'];
} else {
  $userId = (int) ($_SESSION['user_id'] ?? 0);
}

$orders = get_orders_by_user($conn, $userId);
$detailsModel = new OrderDetailsModel();

// preload details per order id
$detailsByOrder = [];
foreach ($orders as $o) {
  $details = $detailsModel->getDetailsByOrderId((int) $o['id']);
  $detailsByOrder[$o['id']] = is_array($details) ? $details : [];
}

$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? 'Guest';
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>User Orders</title>
  <link rel="stylesheet" href="../assets/css/store-style.css">
  <link rel="stylesheet" href="../assets/css/theme.css">
  <link rel="stylesheet" href="../assets/css/pages.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
  <header class="header"
    style="background:var(--card-bg);border-bottom:1px solid rgba(0,0,0,0.06);display:flex;justify-content:space-between;align-items:center;padding:12px 30px;">
    <div style="font-weight:700;color:var(--text);">My Orders</div>
    <nav style="display:flex;gap:15px;font-size:14px;">
      <a href="index.php" style="text-decoration:none;color:var(--primary-color);">Home</a>
      <?php if ($isAdmin): ?>
        <a href="dashboard.php" style="text-decoration:none;color:var(--primary-color);">Dashboard</a>
      <?php endif; ?>
      <a href="../controllers/AuthController.php?action=logout"
        style="text-decoration:none;color:var(--primary-color);">Logout</a>
    </nav>
  </header>

  <main class="app-main">
    <h2 style="margin-bottom:12px;color:var(--text);">Orders for user #<?= htmlspecialchars((string) $userId) ?></h2>

    <?php if (empty($orders)): ?>
      <div class="no-orders"
        style="color:var(--muted);background:rgba(0,0,0,0.02);padding:18px;border-radius:8px;text-align:center;">No orders
        found.</div>
    <?php else: ?>
      <?php foreach ($orders as $o): ?>
        <section class="order-card">
          <header class="order-header">
            <div>
              <strong style="color:var(--text);">Order #<?= htmlspecialchars($o['id']) ?></strong>
              <div class="order-meta">Placed: <?= htmlspecialchars($o['created_at']) ?> — Status:
                <span style="font-weight:600;color:var(--primary-color);"><?= htmlspecialchars($o['order_status']) ?></span>
              </div>
            </div>
            <div style="text-align:right;">
              <div style="font-weight:700;color:var(--primary-color);">
                <?= htmlspecialchars(number_format((float) $o['total_amount'], 2)) ?> $
              </div>
              <div style="font-size:13px;color:var(--muted);">Items: <?= count($detailsByOrder[$o['id']] ?? []) ?></div>
            </div>
          </header>
          <div class="order-body">
            <table class="order-table" style="width:100%;border-collapse:collapse;">
              <thead>
                <tr>
                  <th style="text-align:left;color:var(--text);">Product</th>
                  <th style="text-align:right;width:80px;color:var(--text);">Qty</th>
                  <th style="text-align:right;width:120px;color:var(--text);">Price</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($detailsByOrder[$o['id']] ?? [] as $d): ?>
                  <tr>
                    <td style="color:var(--text);"><?= htmlspecialchars($d['product_name'] ?? ('#' . $d['product_id'])) ?>
                    </td>
                    <td style="text-align:right;color:var(--text);"><?= htmlspecialchars($d['quantity'] ?? 0) ?></td>
                    <td style="text-align:right;color:var(--text);">
                      <?= htmlspecialchars(number_format((float) ($d['price'] ?? 0), 2)) ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

            <!-- Cancel button (only show if order is Pending) -->
            <?php if ($o['order_status'] === 'Pending'): ?>
              <div style="margin-top:12px;display:flex;gap:8px;">
                <button class="btn btn-cancel" onclick="cancelOrder(<?= (int) $o['id'] ?>)" title="Cancel this order">
                  <i class="fa-solid fa-trash"></i> Cancel Order
                </button>
              </div>
            <?php endif; ?>
          </div>
        </section>
      <?php endforeach; ?>
    <?php endif; ?>
  </main>

  <script>
    function cancelOrder(orderId) {
      if (!confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
        return;
      }

      const formData = new FormData();
      formData.append('action', 'cancel');
      formData.append('order_id', orderId);

      fetch('/Giga-store/controllers/OrderController.php', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
      })
        .then(resp => resp.json())
        .then(data => {
          if (data.success) {
            alert('Order cancelled successfully');
            location.reload();
          } else {
            alert('Error: ' + (data.message || 'Failed to cancel order'));
          }
        })
        .catch(err => {
          alert('Network error: ' + err.message);
        });
    }
  </script>

  <script src="../assets/js/theme.js"></script>
</body>

</html>