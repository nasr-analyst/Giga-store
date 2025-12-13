<?php
// Simple order success page used by OrderController.php redirects
$orderId = isset($_GET['order_id']) ? htmlspecialchars((string) $_GET['order_id']) : null;
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Order Success</title>
  <link rel="stylesheet" href="../assets/css/store-style.css">
  <link rel="stylesheet" href="../assets/css/theme.css">
  <link rel="stylesheet" href="../assets/css/pages.css">
</head>

<body>
  <main class="order-success">
    <h1>Thank you — your order is confirmed</h1>
    <?php if ($orderId): ?>
      <p>Your order ID is <strong>#<?= $orderId ?></strong></p>
    <?php else: ?>
      <p>Your order was processed successfully.</p>
    <?php endif; ?>
    <p><a href="index.php">Back to store</a> | <a href="orders.php">View your orders</a></p>
  </main>
  <script src="../assets/js/theme.js"></script>
</body>

</html>