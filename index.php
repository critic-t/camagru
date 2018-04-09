<?php

include './config/conn.php';

// Start Session
session_start();

if(!empty($_SESSION['name'])) { 

    if(isset($_SESSION['email'])) {

        $name = $_SESSION['name'];
        $email = $_SESSION['email'];
        $status = $_SESSION['status'];

        if($email != "" && $status == "logged in")
        {
            header("Location: camagru.php");
            exit();
        }
    }
}

// check Register request
if (!empty($_POST['btnRegister'])) {
    if ($_POST['name'] == "") {
        $register_error_message = 'Name field is required!';
        echo $register_error_message . "<br>";
    } else if ($_POST['email'] == "") {
        $register_error_message = 'Email field is required!';
        echo $register_error_message . "<br>";
    } else if ($_POST['password'] == "") {
        $register_error_message = 'Password field is required!';
        echo $register_error_message . "<br>";
    } else if ($_POST['repeat_password'] == "") {
        $register_error_message = 'Repeat Password field is required!';
        echo $register_error_message . "<br>";
    } else if ($_POST['repeat_password'] != $_POST['password']) {
        $register_error_message = 'Passwords don\'t match!';
        echo $register_error_message . "<br>";
    } else if (strlen($_POST['repeat_password']) < 6) {
        $register_error_message = 'Password must be at least 6 characters!';
        echo $register_error_message . "<br>";
    } else if (!preg_match('/[^a-zA-Z]+/',($_POST['repeat_password']))) {
        $register_error_message = 'Passwords must have at least one special character!';
        echo $register_error_message . "<br>";
    } else {
        try {
            $name = trim($_POST['name']);
            $name = strip_tags($name);
            $name = htmlspecialchars($name);
            
        	$email = trim($_POST['email']);
            $email = strip_tags($email);
            $email = htmlspecialchars($email);

        	$password = trim($_POST['password']);
            $password = strip_tags($password);
            $password = htmlspecialchars($password);

            $enc_password = hash('sha256', $password);
            $confirm_code=md5(uniqid(rand()));
            $emailnotifications = "yes";
            
            // prepare sql and bind parameters
            $stmt = $conn->prepare("INSERT INTO users(name, email, password, emailnotifications, confirmation_code) 
            VALUES(:name, :email, :password, :emailnotifications, :confirmation_code)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $enc_password);
            $stmt->bindParam(':emailnotifications', $emailnotifications);
            $stmt->bindParam(':confirmation_code', $confirm_code);

            $stmt1 = $conn->prepare("SELECT user_id FROM users WHERE email=:email");
            $stmt1->bindParam(':email', $email);
            $stmt1->execute();
            if ($stmt1->rowCount() > 0) {
                echo "Email Is Already In Use! <br>";
            } else{
                $stmt->execute();
                echo "Account Created Login To Continue! <br>";

                // ---------------- SEND MAIL FORM ----------------
                    
                    // send e-mail to ...
                    $to=$email;
                    // Your subject
                    $subject="Your Camagru signup confirmation link here";
                    // From
                    $header="from: Camagru";
                    // Your message
                    $message="Your Confirmation link \r\n";
                    $message.="Click on this link to activate your account \r\n";
                    $message.="http://localhost/camagru/inc/confirmation.php?passkey=$confirm_code";
    
                    // send email
                    $sentmail = mail($to,$subject,$message,$header);

                    // if your email succesfully sent
                    if($sentmail){
                        echo "Your Confirmation link Has Been Sent To Your Email Address.";
                    } else {
                        echo "Cannot send Confirmation link to your e-mail address";
                    }
                
            }
		} catch (PDOException $e) {
			echo "error: " . $sql . "<br>" . $e->getMessage();
		}
        $conn = null;
    }
}

// check Login request
if (!empty($_POST['btnLogin'])) {

    $email = trim($_POST['email']);
    $email = strip_tags($email);
    $email = htmlspecialchars($email);

    $password = trim($_POST['password']);
    $password = strip_tags($password);
    $password = htmlspecialchars($password);
 
    if ($email == "") {
        $login_error_message = 'Email is required!';
        echo $login_error_message . "<br>";
    } else if ($password == "") {
        $login_error_message = 'Password is required!';
        echo $login_error_message . "<br>";
    } else {
    	try {
            $enc_password = hash('sha256', $password);
            $status = "activated";
            // prepare sql and bind parameters
            $stmt2 = $conn->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
            $stmt2->bindParam(':email', $email);
            $stmt2->bindParam(':password', $enc_password);
            $stmt2->execute();
            if ($stmt2->rowCount() > 0) {
                $stmt3 = $conn->prepare("SELECT name FROM users WHERE email=:email AND status=:status");
                $stmt3->bindParam(':email', $email);
                $stmt3->bindParam(':status', $status);
                $stmt3->execute();
                if ($stmt3->rowCount() > 0) {
                    $row = $stmt3->fetch();
                    $name = $row['name'];

                    $_SESSION['name'] = $name;
                    $_SESSION['email'] = $email;
                    $_SESSION['overlay'] = 1;
                    $_SESSION['gallery_count'] = 0;
                    $_SESSION['gallery_start'] = 0;
                    $_SESSION['gallery_end'] = 0;
                    $_SESSION['status'] = "logged in";
                    header("Location: camagru.php");
                } else {
                    echo "Your account is not Activated! Check your email for activation link." . "<br>";
                }
            } else {
                echo "Incorrect user credentials, please try again!" . "<br>";
            }

		} catch (PDOException $e) {
			echo "error: " . $e->getMessage();
		}
		$conn = null;
    }

}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Camagru</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<ul>
        <li><a href="#home">Home</a></li>
        <li><a href="./inc/gallery.php">Gallery</a></li>
</ul>
<div class="container">
    <div class="row">
            <h4>Register</h4>
            <form action="index.php" method="post">
                <div class="form-group">
                    <label for="">Username</label>
                    <input type="text" name="name" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" name="email" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <input type="password" name="password" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="">Repeat Password</label>
                    <input type="password" name="repeat_password" class="form-control"/>
                </div>
                <div class="form-group">
                    <input type="submit" name="btnRegister" class="btn btn-primary" value="Register"/>
                </div>
            </form>
            <h4>Login</h4>
            <form action="index.php" method="post">
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" name="email" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <input type="password" name="password" class="form-control"/>
                </div>
                <div class="form-group">
                    <a href='./inc/initreset.php'>Forgotten Password</a>
                </div>
                <div class="form-group">
                    <input type="submit" name="btnLogin" class="btn btn-primary" value="Login"/>
                </div>
            </form>
    </div>
</div>
 
</body>
</html>