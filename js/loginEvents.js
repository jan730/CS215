let loginForm = document.getElementById("loginForm");
let email = document.getElementById("email");
let password = document.getElementById("password");

email.addEventListener("blur", emailBlurHandler);
password.addEventListener("blur", passwordBlurHandler);
loginForm.addEventListener("submit", loginSubmitHandler);
