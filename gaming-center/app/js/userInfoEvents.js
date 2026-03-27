let userUpdateForm = document.getElementById("user-update-form");
let nickname = document.getElementById("nickname");
let avatar = document.getElementById("avatar");
let dob = document.getElementById("dob");

nickname.addEventListener("blur", nicknameBlurHandler);
//using change on this because blur doesnt work
//this doesnt detect no change though but the submit button will still catch empty avatars
avatar.addEventListener("change", avatarBlurHandler);
dob.addEventListener("blur", dobBlurHandler);

userUpdateForm.addEventListener("submit", userInfoSubmitHandler);
