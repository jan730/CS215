<?php
require_once("db.php");

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data); 
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $nickname = test_input($_GET["nickname"]);
    $avatar = test_input($_GET["avatar"]);
    $dob = test_input($_GET["dob"]);

    $nicknameRegex = "/^\w+$/";
    $avatarRegex = "/^[^\n]+\.[a-zA-Z]{3,4}$/";

    //validate input
    if(!preg_match($nicknameRegex, $nickname)) {
        $errors["nickname"] = "Invalid nickname";
    }
    //to check if it was in the past
    $inputDate = new DateTime($dob);
    $currentDate = new DateTime();  //now
    if($dob == ""||$inputDate > $currentDate){
        $errors["date"] = "Invalid date";
    }

    //avatar validation
    $target_dir = "uploads/";
    $uploadOk = TRUE;

    $imageFileType = strtolower(pathinfo($_FILES["avatar"]["name"],PATHINFO_EXTENSION));
    
    $avatar_path = $target_dir . $uid . "." . $imageFileType;

    //just replace it when we move?
    //remove old avatar if it exists
    // if (file_exists($avatar_path)) {
    //     unlink($avatar_path); // delete old file
    // }

    // Check whether the file is not too large
    if ($_FILES["avatar"]["size"] > 1000000) {
        $errors["avatar"] = "File is too large. Maximum 1MB. ";
        $uploadOk = FALSE;
    }

    // Check image file type
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        $errors["avatar_path"] = "Bad image type. Only JPG, JPEG, PNG & GIF files are allowed. ";
        $uploadOk = FALSE;
    }

    // Check if $uploadOk still TRUE after validations
    if ($uploadOk) {
        // Move the user's avatar to the uploads directory and capture the result as $fileStatus.
        $fileStatus = move_uploaded_file($_FILES["avatar"]["tmp_name"], $avatar_path);

        // Check $fileStatus:
        if (!$fileStatus) {
            // The user's avatar file could not be moved
            // To Do 9a: add a suitable error message to errors array be displayed on the page
            $errors["Server Error"] = "Failed to upload image";
            $uploadOK = FALSE;
        }
    }

    if (empty($errors) && $uploadOK) {
        try {
            $db = new PDO($attr, $db_user, $db_pwd, $options);
            
            $query = "UPDATE Loggers SET nickname='$nickname', avatar_url='$avatar', dob='$dob' WHERE user_id=" . $_SESSION['user_id'];
            $result = $db->query($query);

            if ($result) {
                $response = ['success' => true, 'message' => 'Updated successfully'];
            } else {
                $response = ['success' => false, 'message' => 'Update failed'];
            }
            
            echo json_encode($response);

            $db = null;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}