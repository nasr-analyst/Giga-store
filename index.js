// index.js

// تأكد إن المستخدم عامل تسجيل دخول
const loggedUser = localStorage.getItem("currentUser");
if (!loggedUser) {
  window.location.href = "login.html";
}

// نجيب كل أزرار الشراء والإضافة
const buyButtons = document.querySelectorAll(".btn-buy");
const addButtons = document.querySelectorAll(".btn-add");

// دالة موحدة لإضافة المنتج إلى السلة
function addProductToCart(productElement) {
    const name = productElement.querySelector(".name, .rec-name").textContent;
    // السعر: نأخذ المحتوى ونحذف رمز العملة (إذا كان موجوداً)
    const priceText = productElement.querySelector(".price, .muted").textContent;
    const price = priceText.replace(/\$|EGP/g, "").trim(); 
    const image = productElement.querySelector("img").src;

    const product = { name, price: priceText, image }; // نحفظ النص الأصلي للسعر
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    
    // إضافة المنتج للسلة
    cart.push(product);
    localStorage.setItem("cart", JSON.stringify(cart));
    
    return product;
}

// لما المستخدم يضغط على "Buy Now"
buyButtons.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    const productElement = e.target.closest(".product, .rec-card");
    const product = addProductToCart(productElement); // أضف المنتج إلى السلة
    
    alert(`${product.name} added to cart. Redirecting to checkout.`);
    window.location.href = "checkout.html"; // انتقل مباشرة لصفحة الدفع
  });
});

// لما المستخدم يضغط على "Add to Cart"
addButtons.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    const productElement = e.target.closest(".product");
    const product = addProductToCart(productElement); // أضف المنتج إلى السلة
    
    alert(`${product.name} added to cart!`);
    updateCartCount(); // حدث العدد مباشرة
  });
});

// فتح صفحة الكارت (التي أصبحت الآن صفحة الدفع)
function goToCart() {
  window.location.href = "checkout.html";
}

// تحديث عدد المنتجات في الكارت
function updateCartCount() {
  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  const countEl = document.getElementById("cart-count");
  if (countEl) countEl.textContent = cart.length;
}

// نحدث العدد كل مرة الصفحة تحمل
updateCartCount();