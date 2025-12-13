console.log("Category Filter Activated");


let category_btns = document.querySelectorAll('.category');


let products = document.querySelectorAll('article.product');


category_btns.forEach(btn => {
    btn.onclick = function() {
        
        let selectedId = btn.id;

        category_btns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

    //    كل المنتجات
        if (selectedId == '0') {
            products.forEach(prod => prod.style.display = 'block');
        }
        
        else {
            products.forEach(prod => {
                if (prod.id == selectedId) {
                    prod.style.display = 'block';
                } else {
                    prod.style.display = 'none';
                }
            });
        }
    };
});

// ============= UNIFIED CART SYSTEM =============
// Single source of truth for cart operations
function cartKey(uid) { 
    return `cart:u:${uid}`; 
}

function getUserId() { 
    return (typeof window.CURRENT_USER_ID === 'number') ? window.CURRENT_USER_ID : 0; 
}

function requireLogin() {
    if (getUserId() === 0) {
        alert('Please login first.');
        window.location.href = 'login.php';
        return false;
    }
    return true;
}

function addProductToCart(productElement) {
    if (!requireLogin()) return null;
    
    const nameEl = productElement.querySelector(".name, .rec-name");
    const priceEl = productElement.querySelector(".price, .muted");
    const imgEl = productElement.querySelector("img");
    const name = nameEl ? nameEl.textContent.trim() : 'Unknown';
    const priceText = priceEl ? priceEl.textContent.trim() : '0';
    const price = priceText.replace(/\$|$/g, "").trim();
    const image = imgEl ? imgEl.src : '';
    const productId = parseInt(productElement.getAttribute('data-product-id')) || 0;
    
    const product = { id: productId, name, price: priceText, image, quantity: 1 };
    const uid = getUserId();
    const key = cartKey(uid);
    
    let cart = JSON.parse(localStorage.getItem(key) || '[]');
    cart.push(product);
    localStorage.setItem(key, JSON.stringify(cart));
    updateCartCount();
    return product;
}

// Buy button handler
const buyButtons = document.querySelectorAll(".btn-buy");
buyButtons.forEach((btn) => {
    btn.addEventListener("click", (e) => {
        const productElement = e.target.closest(".product, .rec-card");
        const product = addProductToCart(productElement);
        if (!product) return;
        alert(`${product.name} added to cart. Redirecting to checkout.`);
        window.location.href = "checkout.php";
    });
});

// Add to cart button handler
const addButtons = document.querySelectorAll(".btn-add");
addButtons.forEach((btn) => {
    btn.addEventListener("click", (e) => {
        const productElement = e.target.closest(".product");
        const product = addProductToCart(productElement);
        if (!product) return;
        alert(`${product.name} added to cart!`);
    });
});

function goToCart() {
    if (!requireLogin()) return;
    window.location.href = "checkout.php";
}

function updateCartCount() {
    const uid = getUserId();
    const countEl = document.getElementById("cart-count");
    if (!countEl) return;
    if (uid === 0) { 
        countEl.textContent = '0'; 
        return; 
    }
    const key = cartKey(uid);
    const cart = JSON.parse(localStorage.getItem(key) || '[]');
    countEl.textContent = cart.length;
}

updateCartCount();

// ============= SEARCH FUNCTIONALITY =============
const searchInput = document.getElementById('product-search');
const searchBtn = document.querySelector('.btn-search');

if (searchInput) {
    let debounceTimer = null;
    searchInput.addEventListener('input', (e) => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            filterProducts(e.target.value);
        }, 180);
    });
}

// Trigger search on button click
if (searchBtn) {
    searchBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const query = searchInput ? searchInput.value : '';
        filterProducts(query);
    });
}

function filterProducts(query) {
    query = (query || '').trim().toLowerCase();
    const allProducts = document.querySelectorAll('.product, .rec-card');
    if (!query) {
        allProducts.forEach(p => p.style.display = '');
        return;
    }
    allProducts.forEach(p => {
        const titleEl = p.querySelector('.name, .rec-name');
        const descEl = p.querySelector('.muted');
        const title = titleEl ? titleEl.textContent.toLowerCase() : '';
        const desc = descEl ? descEl.textContent.toLowerCase() : '';
        const match = title.includes(query) || desc.includes(query);
        p.style.display = match ? '' : 'none';
    });
}
