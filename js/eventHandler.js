function emailBlurHandler(event) {
    let input = event.target;
    let error = validateEmail(input.value);

    if (error !== "") {
        showError(input, error);
    } else {
        markValid(input);
    }
}

function passwordBlurHandler(event) {
    let input = event.target;
    let error = validatePassword(input.value);

    if (error !== "") {
        showError(input, error);
    } else {
        markValid(input);
    }
}

function loginSubmitHandler(event) {
    let emailInput = document.getElementById("email");
    let passwordInput = document.getElementById("password");

    let emailError = validateEmail(emailInput.value);
    let passwordError = validatePassword(passwordInput.value);

    if (emailError !== "") {
        showError(emailInput, emailError);
    }

    if (passwordError !== "") {
        showError(passwordInput, passwordError);
    }

    if (emailError !== "" || passwordError !== "") {
        event.preventDefault();
    }
}