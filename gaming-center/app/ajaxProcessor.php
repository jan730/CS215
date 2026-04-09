<?php
session_start();
if (!isset($_SESSION["uid"])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

require_once("db.php");

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data); 
    return $data;
}

$errors = [];
$uid = $_SESSION["uid"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nickname = test_input($_POST["nickname"] ?? '');
    $dob = test_input($_POST["dob"] ?? '');

    $nicknameRegex = "/^\w+$/";

    // Validate input
    if (!preg_match($nicknameRegex, $nickname)) {
        $errors[] = "Invalid nickname";
    }
    $inputDate = new DateTime($dob);
    $currentDate = new DateTime();
    if ($dob == "" || $inputDate > $currentDate) {
        $errors[] = "Invalid date";
    }


    //avatar validation
    $avatar_path = "";
    $avatarRegex = "/^[^\n]+\.[a-zA-Z]{3,4}$/";

    if(isset($_FILES["avatar"])){
        $target_dir = "uploads/";
        $uploadOk = TRUE;

        $imageFileType = strtolower(pathinfo($_FILES["avatar"]["name"],PATHINFO_EXTENSION));
        
        $avatar_path = $target_dir . $uid . "." . $imageFileType;

        //just replace it when we move?
        //remove old avatar if it exists
        // if (file_exists($avatar_path)) {
        //     unlink($avatar_path); // delete old file
        // }

        if (!preg_match($avatarRegex, $avatar_path)) {
            $errors[] = "Invalid avatar";
            $uploadOk = FALSE;
        }

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
                $errors["Server Error"] = "Failed to upload image";
                $uploadOk = FALSE;
            }
        }
        else{
            //if !uploadok
            $errors["Server Error"] = "Uploak not okay";
        }
    }

    if (empty($errors)) {
        try {
            $db = new PDO($attr, $db_user, $db_pwd, $options);
            
            $query = $db->prepare("UPDATE Users SET nickname = ?, dob = ?, avatar_path = ? WHERE uid = ?");
            $query->bindParam(1, $nickname, PDO::PARAM_STR);
            $query->bindParam(2, $dob, PDO::PARAM_STR);
            $query->bindParam(3, $avatar, PDO::PARAM_STR);
            $query->bindParam(4, $uid, PDO::PARAM_INT);
            $result = $query->execute();

            if ($result) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Updated successfully',
                    'nickname' => $nickname,
                    'avatar' => $avatar_path,
                    'dob' => $dob
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Update failed']);
            }

            $db = null;
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    }
}
?>