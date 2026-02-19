// validation.js

function validateEmail(value) {
    if (value === "") {
        return "Email cannot be empty.";
    }

    // simple intro-level email pattern
    let pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!pattern.test(value)) {
        return "Please enter a valid email address.";
    }

    return ""; // no errors
}

function validateNickname(value) {
    if (value === "") {
        return "Nickname cannot be empty.";
    }

    let pattern = /^\w+$/; // letters, digits, underscore
    if (!pattern.test(value)) {
        return "Nickname can only contain letters, digits, and underscores.";
    }

    return "";
}

function validatePassword(value) {
    if (value === "") {
        return "Password cannot be empty.";
    }

    if (value.length < 6) {
        return "Password must be at least 6 characters.";
    }

    if (!/[^A-Za-z]/.test(value)) {
        return "Password must contain at least one non-letter character.";
    }

    if (/\s/.test(value)) {
        return "Password cannot contain spaces.";
    }

    return "";
}

function validateConfirmPassword(passwordValue, confirmValue) {
    if (confirmValue === "") {
        return "Please confirm your password.";
    }

    if (passwordValue !== confirmValue) {
        return "Passwords do not match.";
    }

    return "";
}

function validateDOB(value) {
    if (value === "") {
        return "Date of birth cannot be empty.";
    }

    let date = new Date(value);
    let today = new Date();

    if (isNaN(date.getTime())) {
        return "Please enter a valid date.";
    }

    if (date > today) {
        return "Date of birth must be in the past.";
    }

    return "";
}

function validateAvatarPath(value) {
    if (value === "") {
        return "Avatar path cannot be empty.";
    }

    return "";
}

// DOM helpers
function markValid(input) {
    input.classList.remove("invalid");
    let errorSpan = document.getElementById(input.id + "-error");
    if (errorSpan) {
        errorSpan.textContent = "";
    }
}

function showError(input, message) {
    input.classList.add("invalid");
    let errorSpan = document.getElementById(input.id + "-error");
    if (errorSpan) {
        errorSpan.textContent = message;
    }
}
