// login.js

const form = document.querySelector("#login-form");

form.addEventListener("submit", (e) => {
  e.preventDefault();

  const email = document.querySelector("#email").value.trim();
  const password = document.querySelector("#password").value.trim();

  // التحقق من إن الحقول مش فاضية
  if (!email || !password) {
    alert("Please enter your email and password!");
    return;
  }

  // نجيب المستخدمين المسجلين من localStorage
  const users = JSON.parse(localStorage.getItem("users")) || [];
  console.log(users)

  // نتحقق لو فيه مستخدم بنفس الإيميل والباسورد
  const user = users.find((u) => u.email === email && u.password === password);

  if (!user) {
    alert("Invalid email or password!");
    return;
  }

  // نحفظ المستخدم الحالي في localStorage
  localStorage.setItem("currentUser", JSON.stringify(user));

  alert(`Welcome back, ${user.name}!`);
  window.location.href = "index.html";
});
