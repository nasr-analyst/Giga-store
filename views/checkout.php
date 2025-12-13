<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  $_SESSION['error'] = 'Please login to checkout';
  header('Location: login.php');
  exit;
}
$CURRENT_USER_ID = (int) $_SESSION['user_id'];
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Checkout - Giga Store</title>
  <link rel="stylesheet" href="../assets/css/store-style.css">
  <link rel="stylesheet" href="../assets/css/theme.css">
  <link rel="stylesheet" href="../assets/css/pages.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
  <main class="app-main">
    <div class="checkout-card">
      <h2>Order Summary</h2>
      <div id="product-details"></div>

      <div class="total-summary">
        <strong>Total Amount: <span id="total-price-display">0.00 $</span></strong>
      </div>

      <form id="checkout-form" class="checkout-form">
        <input type="hidden" name="action" value="create">
        <input type="hidden" name="total_amount" id="hidden-total-price">
        <input type="hidden" name="cart_items" id="hidden-cart-items">

        <h3>Shipping Details</h3>

        <div class="form-row">
          <input type="text" name="customer_name" id="customer_name" placeholder="Full Name" required
            value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>">
        </div>

        <div class="form-row">
          <input type="email" name="customer_email" id="customer_email" placeholder="Email Address" required
            value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>">
        </div>

        <div class="form-row">
          <input type="tel" name="customer_phone" id="customer_phone" placeholder="Phone Number" required>
        </div>

        <div class="form-row">
          <textarea name="shipping_address" id="shipping_address" placeholder="Shipping Address" required
            rows="3"></textarea>
        </div>

        <button type="submit" id="confirm-btn" class="btn btn-buy">Confirm Purchase</button>
      </form>

      <div style="display:flex;gap:8px;margin-top:10px;">
        <a href="index.php" class="btn btn-show">Back to Store</a>
        <a href="orders.php" class="btn btn-show">My Orders</a>
      </div>

      <div id="feedback" class="feedback"></div>
    </div>
  </main>

  <script>
    // Expose current user ID to checkout.js
    window.CURRENT_USER_ID = <?= $CURRENT_USER_ID ?>;
  </script>

  <script src="../assets/js/theme.js"></script>
  <script src="../assets/js/checkout.js"></script>
</body>

</html>