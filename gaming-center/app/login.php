<?php
session_start();
require_once("db.php");

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$errors = array();
$email = "";
$password = "";

//if you got here by post by submitting this form
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //make sure the info is not empty and test
    if (empty($_POST["email"])) {
        $errors["empty email"] = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
    }

    if (empty($_POST["password"])) {
        $errors["empty password"] = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
    }

    try {
		$db = new PDO($attr, $db_user, $db_pwd, $options);
	} catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    //get the password hash
    $query = $db->prepare("SELECT uid password FROM Users WHERE email = ?");
    $query->bind_param('s', $email);    //replace the ? with $email, the 's' makes it string
    $query->execute();
    $result = $query->get_result(); //$result is the row containign the pass hash for the email

    if(!$result){
        $errors["database error"] = "Could not retrieve user information";
    } elseif($row = $result->fetch()){
        //if there is a row get the password
        $passhash = $row["password"];

        //check if email and password are a valid match

        //verify password
        if(password_verify($password, $passhash)){
            //the password is the same as the one stored in db after the hash

            //store the user id in the session
            $_SESSION["uid"] = $row["uid"]

            //disconnect db
            $db = null;

            //move to dashboard
            header("Location: dashboard.php");
            exit();
        } else{
            //email will always be correct if it gets to this step though?
            $errors["login"] = "incorrect email or password";
        }
        

    } else{
        $errors["login"] = "incorrect email or password";
    }

    $db = null; //close db

    //print error message
    if (!empty($errors)) {
        foreach($errors as $type => $message) {
            print("$type: $message \n<br />");
        }
    }


}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />

    </head>
    <body>
        <div class="global-formatting">
            <header>
                <div id="logo">
                    <p>Logo here</p>
                </div>
                <div id="page-name">
                    <p>Log In</p>
                </div>
                <div id="user-options">
                    <a href="signup.php">Sign Up</a>
                </div>
            </header>
            <div id="container">
                <main id="centered-main">
                    <div class="form-title">
                        <h2>Log In</h2>
                    </div>
                    <form id="login-form" action="login.php" method="post" class="auth-form">
                        <div class="form-input">
                            <div>
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" />
                                <span class="error" id="email-error"></span>
                            </div>
                            <div>
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" />
                                <span class="error" id="password-error"></span>
                            </div>
                        </div>
                        <div class="form-submit-button">
                            <input type="submit" value="Log In" />
                        </div>
                    </form>
                    <div class="form-note">
                        <a href="signup.php" id="sign-up">Create an account?</a>
                        <!--Forgot password button will require a separate page-->
                        <a href="" id="forgot-pass">Forgot password?</a>
                    </div>    
                </main>
            </div>

            <footer>
                <a href="https://validator.w3.org/check?uri=http%3A%2F%2Fwww.webdev.cs.uregina.ca%2F%7Eizk251%2Fassignments%2FCS215IZ%2Flogin.html&charset=%28detect+automatically%29&doctype=XHTML+1.1&group=0&user-agent=W3C_Validator%2F1.3+" id="xhtml-validator">XHTML 1.1 Validator</a>
                
                <a href="https://jigsaw.w3.org/css-validator/check/referer" id="css-validator">
                        <img style="border:0;width:88px;height:31px"
                            src="https://jigsaw.w3.org/css-validator/images/vcss"
                            alt="Valid CSS!" />
                </a>
            </footer>
        </div>
        <script src="js/eventHandlers.js"></script>
        <script src="js/validation.js"></script>
        <script src="js/loginEvents.js"></script>
    </body>
</html>