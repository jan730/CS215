<?php
//required to be logged in to view this page
session_start();
if (!isset($_SESSION["uid"])) {
    header("Location: login.php");
    exit();
}

//want to query db for nickname, avatar, date of birth
//maybe email as well since we display it on the page

//only want to display if entry !null since default is null

require_once("db.php");

//for data input later
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


$errors = array();
$uid = "";
$email = "";
$nickname = "";
$dob = "";
$avatar_path = "";

//attempt connection
try {
    $db = new PDO($attr, $db_user, $db_pwd, $options);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

//query the nickname, avatar, dob, and email based on the given uid in $_SESSION

if(!isset($_SESSION["uid"])){
    //if the user id is not set then show error
    $errors["uid"] = "User not found";
} else{
    $uid = $_SESSION["uid"];
    $query = $db->prepare("SELECT nickname, email, dob, avatar_path FROM Users WHERE uid = ?");
    $query->bindParam(1, $uid, PDO::PARAM_INT);
    $result = $query->execute();

    if(!$result){
        $errors["database error"] = "Could not retrieve user information";
    } elseif($row = $query->fetch()){
        $nickname = $row["nickname"];
        $email = $row["email"];
        $dob = $row["dob"];
        $avatar_path = $row["avatar_path"];
    } else{
        $errors["fetch error"] = "Could not find row";
    }
}

//update info if by post
if($_SERVER["REQUEST_METHOD"] == "POST"){

    //delete user delete was pressed
    //delete account and return to login if delete account is confirmed
    if (isset($_POST['delete'])) {
        $query = $db->prepare("DELETE FROM Users WHERE uid=?");
        $query->bindParam(1, $uid, PDO::PARAM_INT);
        $result = $query->execute();

        if(!$result){
            $errors["database error"] = "Could not delete user information";
        } else{
            session_destroy();
            header("Location: login.php");
            exit();
        }
    }
    /*
    //if update user info instead
    $nicknameRegex = "/^\w+$/";
    $avatarRegex = "/^[^\n]+\.[a-zA-Z]{3,4}$/";

    //store input
    if (empty($_POST["nickname"])) {
        $errors["empty nickname"] = "Nickname is empty";
    } else {
        $nickname = test_input($_POST["nickname"]);
    }
    if (empty($_POST["dob"])) {
        $errors["empty dob"] = "Date of birth is empty";
    } else {
        $dob = test_input($_POST["dob"]);
    }

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
            $errors["Server Error"] = "Failed to upload image";
            $uploadOk = FALSE;
        }
    }
    
    if(empty($errors) && $uploadOk){
        $query = $db->prepare("UPDATE Users SET nickname = ?, dob = ?, avatar_path = ? WHERE uid = ?");
        $query->bindParam(1, $nickname, PDO::PARAM_STR);
        $query->bindParam(2, $dob, PDO::PARAM_STR);
        $query->bindParam(3, $avatar_path, PDO::PARAM_STR);
        $query->bindParam(4, $uid, PDO::PARAM_INT);
        $result = $query->execute();

        if(!$result){
            $errors["update failed"] = "Failed to update user info";
        } else{
            //disconnect db and reload page
            $db = null;

            header("Location: userInfo.php");
            exit();
        }
    }
    */
}

//close connection
$db = null;

//display error if there are errors
if (!empty($errors)) {
    //temp
    // foreach($errors as $type => $message) {
    //     print("$type: $message \n<br />");
    // }
    $_SESSION["error"] = "Update failed";
    header("Location: userInfo.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>User Info</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
    </head>
    <body>
        <div class="global-formatting">
            <header>
                <div id="logo">
                    <a href="dashboard.php">Logo here</a>
                </div>
                <div id="page-name">
                    <p>User Dashboard</p>
                </div>
                <div id="user-options">
                    <p><a href="logout.php">Log Out</a></p> 
                </div>
            </header>
            <div id="user-dashboard-body">
                <div id="user-dashboard-container">
                    <div id="user-image-section">
                        <img id="avatar-image" src="<?= $avatar_path ? $avatar_path: 'uploads/1.png' ?>" />
                    </div>
                    
                    <div id="user-info-display">
                        <h3>User Information</h3>
                        <div id="nickname-display">
                            <strong>nickname: </strong>
                            <span id="nickname-value"><?= $nickname ? htmlspecialchars($nickname) : 'nickname not found' ?></span>
                        </div>
                        <div id="email-display">
                            <strong>email: </strong>
                            <span id="email-value"><?= $email ? htmlspecialchars($email) : 'email not found' ?></span>
                        </div>
                        <div id="dob-display">
                            <strong>date of birth: </strong>
                            <span id="dob-value"><?= $dob ? htmlspecialchars($dob) : 'date of birth not found' ?></span>
                        </div>
                    </div>
                    
                    <div id="user-update-section">
                        <h3>Update User Information</h3>
                        <?php
                        if(isset($_SESSION["error"])){
                        ?>
                            <p id="session-error"> 
                                <?php
                                echo $_SESSION["error"];
                                unset($_SESSION["error"]);
                                ?>
                            </p>
                        <?php
                        }
                        ?>
                        <div id="update-result" class="hidden"></div>
                        <form action="" method="post" id="user-update-form" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="nickname">Nickname:</label>
                                <input type="text" id="nickname" name="nickname" value="<?= $nickname ? $nickname : 'nickname not found' ?>" />
                                <span class="error" id="nickname-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="avatar">Avatar:</label>
                                <input type="file" accept="image/*" id="avatar" name="avatar" />
                                <span class="error" id="avatar-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="dob">Date of Birth (dd/mm/yy):</label>
                                <input type="date" id="dob" name="dob" value="<?= $dob ? $dob : '' ?>"/>
                                <span class="error" id="dob-error"></span>
                            </div>
                            <div class="update-btn">
                                <input type="submit" value="Update" />
                            </div>
                        </form>
                    </div>
                </div>
                
                <div id="user-dashboard-footer">
                    <!-- <p><a href="userInfo.php">Delete Account</a></p> -->
                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete your account?');">
                        <button type="submit" name="delete">Delete Account?</button>
                    </form>
                    <p><a href="gameHistory.php">Gaming History</a></p>
                </div>
            </div>

            <footer>
                <a href="https://validator.w3.org/check?uri=http%3A%2F%2Fwww.webdev.cs.uregina.ca%2F%7Eizk251%2Fassignments%2FCS215IZ%2FuserInfo.html&charset=%28detect+automatically%29&doctype=XHTML+1.1&group=0&user-agent=W3C_Validator%2F1.3+" id="xhtml-validator">XHTML 1.1 Validator</a>
                
                <a href="https://jigsaw.w3.org/css-validator/check/referer" id="css-validator">
                        <img style="border:0;width:88px;height:31px"
                            src="https://jigsaw.w3.org/css-validator/images/vcss"
                            alt="Valid CSS!" />
                </a>
            </footer>
        </div>
        <script src="js/eventHandlers.js"></script>
        <script src="js/validation.js"></script>
        <script src="js/userInfoEvents.js"></script>
    </body>
</html>