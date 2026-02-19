let signupForm = document.getElementById("signup-form");
let email = signupForm.querySelector("#email");
let nickname = signupForm.querySelector("#nickname");
let password = signupForm.querySelector("#password");
let confirmPassword = signupForm.querySelector("#confirm-password");

signupForm.addEventListener("submit", signUpSubmitHandler);