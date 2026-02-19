let signupForm = document.getElementById("signup-form");
let email = signupForm.querySelector("#email");
let nickname = signupForm.querySelector("#nickname");
let password = signupForm.querySelector("#password");
let confirmPassword = signupForm.querySelector("#cpassword");

email.addEventListener("blur", emailBlurHandler);
nickname.addEventListener("blur", nicknameBlurHandler);
password.addEventListener("blur", passwordBlurHandler);
confirmPassword.addEventListener("blur", confirmPasswordBlurHandler);

signupForm.addEventListener("submit", signUpSubmitHandler);