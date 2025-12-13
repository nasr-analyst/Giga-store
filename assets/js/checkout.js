// Checkout page logic — handles cart display, quantity controls, and order submission

(function () {
  const CURRENT_USER_ID = window.CURRENT_USER_ID || 0;

  function cartKey(uid) {
    return `cart:u:${uid}`;
  }

  function aggregateCart(cart) {
    const aggregated = {};
    cart.forEach(item => {
      const id = item.id || 0;
      if (!aggregated[id]) {
        aggregated[id] = { ...item, quantity: 0 };
      }
      aggregated[id].quantity += (item.quantity || 1);
    });
    return Object.values(aggregated);
  }

  const container = document.getElementById('product-details');
  const totalDisplay = document.getElementById('total-price-display');
  const hiddenTotal = document.getElementById('hidden-total-price');
  const hiddenCart = document.getElementById('hidden-cart-items');
  const checkoutForm = document.getElementById('checkout-form');
  const confirmBtn = document.getElementById('confirm-btn');
  const feedback = document.getElementById('feedback');

  function updateTotals() {
    const key = cartKey(CURRENT_USER_ID);
    const cart = JSON.parse(localStorage.getItem(key) || '[]');
    const aggregated = aggregateCart(cart);

    let total = 0;
    aggregated.forEach(p => {
      const priceValue = parseFloat(String(p.price).replace(/[^\d.]/g, '')) || 0;
      total += priceValue * p.quantity;
    });

    totalDisplay.textContent = `${total.toFixed(2)} $`;
    hiddenTotal.value = total.toFixed(2);

    const cartForBackend = aggregated.map(item => ({
      id: item.id || 0,
      quantity: item.quantity || 1,
      price: parseFloat(String(item.price).replace(/[^\d.]/g, '')) || 0
    }));
    hiddenCart.value = JSON.stringify(cartForBackend);
  }

  function renderCart() {
    const key = cartKey(CURRENT_USER_ID);
    const cart = JSON.parse(localStorage.getItem(key) || '[]');

    if (!cart.length) {
      container.innerHTML = '<p>Your cart is empty.</p>';
      totalDisplay.textContent = '0.00 $';
      hiddenTotal.value = '0.00';
      hiddenCart.value = '[]';
      confirmBtn.disabled = true;
      return;
    }

    const aggregated = aggregateCart(cart);
    confirmBtn.disabled = false;

    const ul = document.createElement('ul');
    ul.style.listStyle = 'none';
    ul.style.padding = '0';

    aggregated.forEach((p) => {
      const li = document.createElement('li');
      li.style.display = 'flex';
      li.style.alignItems = 'center';
      li.style.gap = '12px';
      li.style.padding = '12px 0';
      li.style.borderBottom = '1px dashed rgba(0,0,0,0.04)';
      li.dataset.productId = p.id;

      // Product image
      const img = document.createElement('img');
      img.src = p.image || '../assets/images/holder.jpg';
      img.style.width = '60px';
      img.style.height = '60px';
      img.style.objectFit = 'contain';
      img.style.borderRadius = '6px';
      img.style.flexShrink = '0';

      // Product info
      const info = document.createElement('div');
      info.style.flex = '1';
      info.innerHTML = `<strong>${p.name}</strong>`;

      // Quantity controls
      const qtyControl = document.createElement('div');
      qtyControl.style.display = 'flex';
      qtyControl.style.alignItems = 'center';
      qtyControl.style.gap = '6px';
      qtyControl.style.flexShrink = '0';

      const minusBtn = document.createElement('button');
      minusBtn.type = 'button';
      minusBtn.textContent = '−';
      minusBtn.style.background = '#ddd';
      minusBtn.style.border = 'none';
      minusBtn.style.width = '24px';
      minusBtn.style.height = '24px';
      minusBtn.style.borderRadius = '4px';
      minusBtn.style.cursor = 'pointer';
      minusBtn.addEventListener('click', () => updateQuantity(p.id, -1));

      const qtySpan = document.createElement('span');
      qtySpan.textContent = p.quantity;
      qtySpan.style.minWidth = '20px';
      qtySpan.style.textAlign = 'center';

      const plusBtn = document.createElement('button');
      plusBtn.type = 'button';
      plusBtn.textContent = '+';
      plusBtn.style.background = 'var(--primary-color)';
      plusBtn.style.color = 'white';
      plusBtn.style.border = 'none';
      plusBtn.style.width = '24px';
      plusBtn.style.height = '24px';
      plusBtn.style.borderRadius = '4px';
      plusBtn.style.cursor = 'pointer';
      plusBtn.addEventListener('click', () => updateQuantity(p.id, 1));

      qtyControl.appendChild(minusBtn);
      qtyControl.appendChild(qtySpan);
      qtyControl.appendChild(plusBtn);

      // Price
      const priceSpan = document.createElement('strong');
      priceSpan.style.color = 'var(--primary-color)';
      priceSpan.style.minWidth = '80px';
      priceSpan.style.textAlign = 'right';
      priceSpan.textContent = p.price;

      li.appendChild(img);
      li.appendChild(info);
      li.appendChild(qtyControl);
      li.appendChild(priceSpan);
      ul.appendChild(li);
    });

    container.innerHTML = '';
    container.appendChild(ul);
    updateTotals();
  }

  function updateQuantity(productId, delta) {
    const key = cartKey(CURRENT_USER_ID);
    let cart = JSON.parse(localStorage.getItem(key) || '[]');

    cart = cart.map(item => {
      if (item.id === productId) {
        const newQty = (item.quantity || 1) + delta;
        return newQty > 0 ? { ...item, quantity: newQty } : null;
      }
      return item;
    }).filter(item => item !== null);

    if (cart.length === 0) {
      localStorage.removeItem(key);
    } else {
      localStorage.setItem(key, JSON.stringify(cart));
    }

    renderCart();
  }

  // Initial render
  renderCart();

  // Form submission
  checkoutForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    feedback.textContent = '';

    const key = cartKey(CURRENT_USER_ID);
    const cart = JSON.parse(localStorage.getItem(key) || '[]');
    if (!cart.length) {
      feedback.textContent = 'Cart is empty';
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

      if (data && data.success) {
        localStorage.removeItem(key);
        window.location.href = 'order_success.php?order_id=' + encodeURIComponent(data.order_id);
      } else {
        feedback.textContent = data.message || 'Order failed';
        confirmBtn.disabled = false;
        confirmBtn.textContent = orig;
      }
    } catch (err) {
      feedback.textContent = 'Network error: ' + err.message;
      confirmBtn.disabled = false;
      confirmBtn.textContent = orig;
    }
  });
})();