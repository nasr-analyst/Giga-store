// register.js

// نجيب عناصر الفورم
const form = document.querySelector("#register-form");

form.addEventListener("submit", (e) => {
  e.preventDefault();

  const name = document.querySelector("#name").value.trim();
  const email = document.querySelector("#email").value.trim();
  const password = document.querySelector("#password").value.trim();

  // التحقق من إن الحقول مش فاضية
  if (!name || !email || !password) {
    alert("Please fill all fields!");
    return;
  }

  // نجيب المستخدمين الموجودين قبل كده
  const users = JSON.parse(localStorage.getItem("users")) || [];

  // التحقق إذا الإيميل متسجل قبل كده
  const existingUser = users.find((u) => u.email === email);
  if (existingUser) {
    alert("This email is already registered!");
    return;
  }

  // نضيف المستخدم الجديد
  const newUser = { name, email, password };
  users.push(newUser);
  localStorage.setItem("users", JSON.stringify(users));

  alert("Registration successful! Please log in.");
  window.location.href = "login.html";
});
