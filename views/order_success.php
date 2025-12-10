<?php
// Simple order success page used by OrderController.php redirects
$orderId = isset($_GET['order_id']) ? htmlspecialchars((string)$_GET['order_id']) : null;
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Order Success</title>
  <link rel="stylesheet" href="../assets/css/store-style.css">
</head>
<body>
  <main style="padding:40px; text-align:center;">
    <h1>Thank you — your order is confirmed</h1>
    <?php if ($orderId): ?>
      <p>Your order ID is <strong>#<?= $orderId ?></strong></p>
    <?php else: ?>
      <p>Your order was processed successfully.</p>
    <?php endif; ?>
    <p><a href="index.php">Back to store</a></p>
  </main>
</body>
</html>