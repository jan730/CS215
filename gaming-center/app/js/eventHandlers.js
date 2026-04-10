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
    event.preventDefault();

    let nicknameInput = document.getElementById("nickname");
    let avatarInput = document.getElementById("avatar");
    let dobInput = document.getElementById("dob");

    //for post
    let formData = new FormData();

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
        return;
    }

    formData.append("nickname", nicknameInput.value);
    formData.append("dob", dobInput.value);

    if (avatarInput.files[0]) {
        formData.append("avatar", avatarInput.files[0]);
    }


    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            let resultDiv = document.getElementById("update-result");
            resultDiv.classList.remove("hidden", "update-success", "update-error");

            if (xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                if (response.success) {
                    resultDiv.textContent = response.message || "Updated successfully!";
                    resultDiv.classList.add("update-success");

                    let nicknameValue = document.getElementById("nickname-value");
                    let dobValue = document.getElementById("dob-value");
                    let avatarImage = document.getElementById("avatar-image");

                    if (nicknameValue && response.nickname) {
                        nicknameValue.textContent = response.nickname;
                    }
                    if (dobValue && response.dob) {
                        dobValue.textContent = response.dob;
                    }
                    if (avatarImage && response.avatar) {
                        avatarImage.src = response.avatar + "?t=" + new Date().getTime();
                    }
                } else {
                    resultDiv.textContent = response.message || "Failed to update information.";
                    resultDiv.classList.add("update-error");
                }
            } else {
                resultDiv.textContent = "Update request failed. Please try again.";
                resultDiv.classList.add("update-error");
            }

            resultDiv.classList.remove("hidden");
        }
    };

    xhr.open("POST", "ajaxProcessor.php", true);
    
    xhr.send(formData);
}