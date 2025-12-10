<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
  $_SESSION['error'] = 'Please login to checkout';
  header('Location: login.php');
  exit;
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Checkout - Giga Store</title>
  <link rel="stylesheet" href="../assets/css/store-style.css">
</head>

<body>
  <main class="checkout-page">
    <div class="checkout-card">
      <h2>Order Summary</h2>
      <div id="product-details"></div>

      <div class="total-summary">
        <strong>Total Amount: <span id="total-price-display">0.00 EGP</span></strong>
      </div>

      <form id="checkout-form" style="margin-top:20px; text-align:left;">
        <input type="hidden" name="action" value="create">
        <input type="hidden" name="total_amount" id="hidden-total-price">
        <input type="hidden" name="cart_items" id="hidden-cart-items">

        <h3>Shipping Details</h3>

        <div style="margin-bottom:10px;">
          <input type="text" name="customer_name" id="customer_name" placeholder="Full Name" required
            style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
        </div>

        <div style="margin-bottom:10px;">
          <input type="email" name="customer_email" id="customer_email" placeholder="Email Address" required
            style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
        </div>

        <div style="margin-bottom:10px;">
          <input type="tel" name="customer_phone" id="customer_phone" placeholder="Phone Number" required
            style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
        </div>

        <div style="margin-bottom:15px;">
          <textarea name="shipping_address" id="shipping_address" placeholder="Shipping Address" required rows="3"
            style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; font-family:inherit;"></textarea>
        </div>

        <button type="submit" id="confirm-btn" class="btn btn-buy" style="width:100%;">Confirm Purchase</button>
      </form>

      <button id="back-btn" class="btn btn-add" style="margin-top:10px;">Back to Store</button>
      <div id="feedback" style="margin-top:12px;color:#b33;"></div>
    </div>
  </main>

  <script>
    // Render cart from localStorage, prepare hidden inputs
    const container = document.getElementById('product-details');
    const totalDisplay = document.getElementById('total-price-display');
    const hiddenTotal = document.getElementById('hidden-total-price');
    const hiddenCart = document.getElementById('hidden-cart-items');
    const checkoutForm = document.getElementById('checkout-form');
    const confirmBtn = document.getElementById('confirm-btn');
    const backBtn = document.getElementById('back-btn');
    const feedback = document.getElementById('feedback');

    function renderCart() {
      const cart = JSON.parse(localStorage.getItem('cart') || '[]');
      if (!cart.length) {
        container.innerHTML = '<p>Your cart is empty.</p>';
        totalDisplay.textContent = '0.00 EGP';
        hiddenTotal.value = '0.00';
        hiddenCart.value = '[]';
        confirmBtn.disabled = true;
        return;
      }
      confirmBtn.disabled = false;
      let total = 0;
      const ul = document.createElement('ul');
      ul.style.listStyle = 'none';
      ul.style.padding = '0';
      cart.forEach((p, idx) => {
        const priceValue = parseFloat(String(p.price).replace(/[^\d.]/g, '')) || 0;
        total += priceValue * (p.quantity || 1);
        const li = document.createElement('li');
        li.style.display = 'flex';
        li.style.justifyContent = 'space-between';
        li.style.padding = '8px 0';
        li.innerHTML = `<span style="flex:1">${p.name} × ${p.quantity || 1}</span><strong>${p.price}</strong>`;
        ul.appendChild(li);
      });
      container.innerHTML = '';
      container.appendChild(ul);
      totalDisplay.textContent = `${total.toFixed(2)} EGP`;
      hiddenTotal.value = total.toFixed(2);

      const cartForBackend = cart.map(item => ({
        id: item.id || 0,
        quantity: item.quantity || 1,
        price: parseFloat(String(item.price).replace(/[^\d.]/g, '')) || 0
      }));
      hiddenCart.value = JSON.stringify(cartForBackend);
    }

    renderCart();

    checkoutForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      feedback.textContent = '';
      const cart = JSON.parse(localStorage.getItem('cart') || '[]');
      if (!cart.length) { feedback.textContent = 'Cart is empty'; return; }

      // Basic client-side validation
      const name = document.getElementById('customer_name').value.trim();
      const email = document.getElementById('customer_email').value.trim();
      const phone = document.getElementById('customer_phone').value.trim();
      const address = document.getElementById('shipping_address').value.trim();
      if (!name || !email || !phone || !address) {
        feedback.textContent = 'Please fill shipping details';
        return;
      }

      confirmBtn.disabled = true;
      const orig = confirmBtn.textContent;
      confirmBtn.textContent = 'Processing...';

      const formData = new FormData(checkoutForm);

      try {
        const resp = await fetch('/Giga-store/controllers/OrderController.php', {
          method: 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          credentials: 'same-origin'
        });

        const ct = resp.headers.get('content-type') || '';
        let data;
        if (ct.includes('application/json')) data = await resp.json();
        else data = { success: resp.ok, message: await resp.text() };

        if (!resp.ok) {
          feedback.textContent = data.message || ('Server error: ' + resp.status);
        } else if (data && data.success) {
          localStorage.removeItem('cart');
          window.location.href = 'order_success.php?order_id=' + encodeURIComponent(data.order_id || '');
        } else {
          feedback.textContent = data.message || 'Order failed';
        }
      } catch (err) {
        feedback.textContent = 'Network error: ' + err.message;
      } finally {
        confirmBtn.disabled = false;
        confirmBtn.textContent = orig;
      }
    });

    backBtn.addEventListener('click', () => window.location.href = 'index.php');
  </script>
</body>

</html>