function emailBlurHandler(event) {
    let input = event.target;
    let error = validateEmail(input.value);

    if (error !== "") {
        showError(input, error);
    } else {
        markValid(input);
    }
}

function nicknameBlurHandler(event) {
    let input = event.target;
    let error = validateNickname(input.value);

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

function confirmPasswordBlurHandler(event) {
    let input = event.target;
    let passwordInput = document.getElementById("password");
    let error = validateConfirmPassword(passwordInput.value, input.value);

    if (error !== "") {
        showError(input, error);
    } else {
        markValid(input);
    }
}

function avatarBlurHandler(event) {
    let input = event.target;
    let error = validateAvatarPath(input.value);

    if (error !== "") {
        showError(input, error);
    } else {
        markValid(input);
    }
}

function dobBlurHandler(event) {
    let input = event.target;
    let error = validateDOB(input.value);

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

function signUpSubmitHandler(event) {
    let emailInput = document.getElementById("email");
    let nicknameInput = document.getElementById("nickname");
    let passwordInput = document.getElementById("password");
    let confirmPasswordInput = document.getElementById("cpassword");

    // Run validators
    let emailError = validateEmail(emailInput.value);
    let nicknameError = validateNickname(nicknameInput.value);
    let passwordError = validatePassword(passwordInput.value);
    let confirmError = validateConfirmPassword(passwordInput.value, confirmPasswordInput.value);

    // Show or clear errors
    if (emailError !== "") {
        showError(emailInput, emailError);
    } else {
        markValid(emailInput);
    }

    if (nicknameError !== "") {
        showError(nicknameInput, nicknameError);
    } else {
        markValid(nicknameInput);
    }

    if (passwordError !== "") {
        showError(passwordInput, passwordError);
    } else {
        markValid(passwordInput);
    }

    if (confirmError !== "") {
        showError(confirmPasswordInput, confirmError);
    } else {
        markValid(confirmPasswordInput);
    }

    // Stop submission if any errors exist
    if (
        emailError !== "" ||
        nicknameError !== "" ||
        passwordError !== "" ||
        confirmError !== ""
    ) {
        event.preventDefault();
    }
}

function userInfoSubmitHandler(event) {
    let nicknameInput = document.getElementById("nickname");
    let avatarInput = document.getElementById("avatar");
    let dobInput = document.getElementById("dob");

    let nicknameError = validateNickname(nicknameInput.value);
    let avatarError = validateAvatarPath(avatarInput.value);
    let dobError = validateDOB(dobInput.value);

    if (nicknameError !== "") {
        showError(nicknameInput, nicknameError);
    } else {
        markValid(nicknameInput);
    }

    if (avatarError !== "") {
        showError(avatarInput, avatarError);
    } else {
        markValid(avatarInput);
    }

    if (dobError !== "") {
        showError(dobInput, dobError);
    } else {
        markValid(dobInput);
    }

    if (nicknameError !== "" || avatarError !== "" || dobError !== "") {
        event.preventDefault();
        return;
    }

    event.preventDefault();

    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.success) {
                document.getElementById("update-success").classList.remove("hidden");
                document.getElementById("update-error").classList.add("hidden");
            } else {
                document.getElementById("update-error").textContent = response.message;
                document.getElementById("update-error").classList.remove("hidden");
                document.getElementById("update-success").classList.add("hidden");
            }
        }
    };

    xhr.open("GET", "ajaxProcessor.php?nickname=" + encodeURIComponent(nicknameInput.value) 
    + "&avatar=" + encodeURIComponent(avatarInput.value) 
    + "&dob=" + encodeURIComponent(dobInput.value), true);
    
    xhr.send();
}