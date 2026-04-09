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

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $nickname = test_input($_GET["nickname"] ?? '');
    $avatar = test_input($_GET["avatar"] ?? '');
    $dob = test_input($_GET["dob"] ?? '');

    $nicknameRegex = "/^\w+$/";
    $avatarRegex = "/^[^\n]+\.[a-zA-Z]{3,4}$/";

    // Validate input
    if (!preg_match($nicknameRegex, $nickname)) {
        $errors[] = "Invalid nickname";
    }
    $inputDate = new DateTime($dob);
    $currentDate = new DateTime();
    if ($dob == "" || $inputDate > $currentDate) {
        $errors[] = "Invalid date";
    }
    if (!preg_match($avatarRegex, $avatar)) {
        $errors[] = "Invalid avatar path";
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
                echo json_encode(['success' => true, 'message' => 'Updated successfully']);
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