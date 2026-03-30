<?php
require_once("db.php");

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$errors = array();
$email = "";
$nickname = "";
$password = "";

//if you got here by post by submitting this form
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //make sure the info is not empty and test
    if (empty($_POST["email"])) {
        $errors["empty email"] = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
    }

    if (empty($_POST["nickname"])) {
        $errors["empty nickname"] = "Nickname is required";
    } else {
        $nickname = test_input($_POST["nickname"]);
    }

    if (empty($_POST["password"])) {
        $errors["empty password"] = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
    }

    $emailRegex = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/";
    $nicknameRegex = "/^\w+$/";

    if(!preg_match($emailRegex, $email)) {
        $errors["email"] = "Invalid email";
    }
    if(!preg_match($nicknameRegex, $nickname)) {
        $errors["nickname"] = "Invalid nickname";
    }
    //mb_strlen() is len of str in chars
    if($password == ""||mb_strlen($password) < 6||!preg_match(/[^A-Za-z]/, $password)||preg_match(/\s/, $password)) {
        $errors["password"] = "Invalid password";
    }

    try {
		$db = new PDO($attr, $db_user, $db_pwd, $options);
	} catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    $query = $db->prepare("SELECT 1 FROM Users WHERE email = ?");
    $query->bindParam(1, $email, PDO::PARAM_STR);    //replace the ? with $email, the 's' makes it string
    $result = $query->execute();

    if(!$result){
        $errors["database error"] = "Could not retrieve user information";
    }

    $match = $query->fetch();

    if ($match) {
        $errors["account taken"] = "A user with that email already exists.";
    }

    //if there are no errors
    if(empty($errors)){
        $passhash = password_hash($password, PASSWORD_DEFAULT);
        //store info
        $query = $db->prepare("INSERT INTO Users (email, nickname, password) VALUES (?, ?, ?)"); //dob and avatar are null by default
        $query->bindParam(1, $email, PDO::PARAM_STR);
        $query->bindParam(2, $nickname, PDO::PARAM_STR);
        $query->bindParam(3, $passhash, PDO::PARAM_STR);
        $result = $query->execute();

        $db = null; //close connection

        if(!$result){
            $errors["user insert"] = "Failed to insert user";
        } else{
            //go to login after successful account setup
            header("Location: login.php");
            exit();
        }
    }
    
    $db = null; //still want to close connection if there are errors

    //print error message
    if (!empty($errors)) {
        // foreach($errors as $type => $message) {
        //     print("$type: $message \n<br />");
        // }
        $_SESSION["error"] = "Sign up failed";
        header("Location: signup.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Signup</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />

        <script src="js/eventHandlers.js"></script>
    </head>
    <body>
        <div class="global-formatting">
            <header>
                <div id="logo">
                    <p>Logo here</p>
                </div>
                <div id="page-name">
                    <p>Sign Up</p>
                </div>
                <div id="user-options">
                    <a href="login.php">Log In</a>
                </div>
            </header>
            <div id="container">
                <main id="centered-main">
                    <div class="form-title">
                        <h2>Sign Up</h2>
                    </div>
                    <form id="signup-form" action="" method="post" class="auth-form">
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
                        <div class="form-input">
                            <div>
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" />
                                <span class="error" id="email-error"></span>
                            </div>
                            <div>
                                <label for="nickname">Nickname</label>
                                <input type="text" id="nickname" name="nickname" />
                                <span class="error" id="nickname-error"></span>
                            </div>
                            <div>
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" />
                                <span class="error" id="password-error"></span>
                            </div>
                            <div>
                                <label for="cpassword">Confirm password</label>
                                <input type="password" id="cpassword" name="cpassword" />
                                <span class="error" id="cpassword-error"></span>
                            </div>
                        </div>
                        <div class="form-submit-button">
                            <input type="submit" value="Sign Up" />
                        </div>
                    </form>
                    <div class="form-note">
                        <a href="login.php" id="log-in">Already have an account?</a>
                    </div>    
                </main>
            </div>
            
            <footer>
                <a href="https://validator.w3.org/check?uri=http%3A%2F%2Fwww.webdev.cs.uregina.ca%2F%7Eizk251%2Fassignments%2FCS215IZ%2Fsignup.html&charset=%28detect+automatically%29&doctype=XHTML+1.1&group=0&user-agent=W3C_Validator%2F1.3+" id="xhtml-validator">XHTML 1.1 Validator</a>
                
                <a href="https://jigsaw.w3.org/css-validator/check/referer" id="css-validator">
                        <img style="border:0;width:88px;height:31px"
                            src="https://jigsaw.w3.org/css-validator/images/vcss"
                            alt="Valid CSS!" />
                </a>
            </footer>
        </div>
        <script src="js/validation.js"></script>
        <script src="js/signupEvents.js"></script>
    </body>
</html>