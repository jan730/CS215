let loginForm = document.getElementById("login-form");
let email = document.getElementById("email");
let password = document.getElementById("password");

email.addEventListener("blur", emailBlurHandler);
password.addEventListener("blur", passwordBlurHandler);
loginForm.addEventListener("submit", loginSubmitHandler);
