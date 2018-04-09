<?php

include '../config/conn.php';

session_start();
$name = $_SESSION['name'];
$email = $_SESSION['email'];
$status = $_SESSION['status'];

if($email == "" && $status != "logged in")
{
    header("Location: ../index.php");
}

// check Register request
if (!empty($_POST['btnUpdate'])) {

    $id = 0;
    $stmt_user = $conn->prepare("SELECT * FROM users WHERE email=:email");
    $stmt_user->bindValue(":email", $email);

    // initialise an array for the results 
    $image = array();
    if ($stmt_user->execute()) {
        while ($row = $stmt_user->fetch(PDO::FETCH_ASSOC)) {
            $image[] = $row;
            $id = $row['id'];
        }
    }

    $newname = $_POST['name'];
    $newemail = $_POST['email'];
    $newenote = $_POST['enote'];
    $password = $_POST['password'];
    $repeatedpassword = $_POST['repeat_password'];
    $encpassword = hash('sha256', $password);

    //echo $id . '</br>';
    //echo $newname . '</br>';
    //echo $newemail . '</br>';
    //echo $password . '</br>';
    //echo $repeatedpassword . '</br>';
    //echo $encpassword . '</br>';
   
        if ($newname != "" && $id != 0)
        {
            try 
            {
                // prepare sql and bind parameters
                $stmt = $conn->prepare("UPDATE users SET name=:name  
                WHERE id=:id");
                $stmt->bindParam(':name', $newname);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
            } catch (PDOException $e) {
			    echo "error: " . $sql . "<br>" . $e->getMessage();
		    }
        }
      
        if ($newemail != "" && $id != 0)
        {    
            try 
            {
                // prepare sql and bind parameters
                $stmt_email = $conn->prepare("UPDATE users SET email=:email  
                WHERE id=:id");
                $stmt_email->bindParam(':email', $newemail);
                $stmt_email->bindParam(':id', $id);
                $stmt_email->execute();
		    } catch (PDOException $e) {
			    echo "error: " . $sql . "<br>" . $e->getMessage();
		    }
        }

        if ($newenote != "unchanged" && $id != 0)
        {    
            try 
            {
                // prepare sql and bind parameters
                $stmt_enote = $conn->prepare("UPDATE users SET emailnotifications=:emailnotifications  
                WHERE id=:id");
                $stmt_enote->bindParam(':emailnotifications', $newenote);
                $stmt_enote->bindParam(':id', $id);
                $stmt_enote->execute();
		    } catch (PDOException $e) {
			    echo "error: " . $sql . "<br>" . $e->getMessage();
		    }
        }
        
        if ($password != "" && $repeatedpassword != "" && $id != 0)
        {
        
            if ($_POST['repeat_password'] != $_POST['password']) {
                $register_error_message = 'Passwords don\'t match!';
                echo $register_error_message . "<br>";
            } else if (strlen($_POST['repeat_password']) < 6) {
                $register_error_message = 'Password must be at least 6 characters!';
                echo $register_error_message . "<br>";
            } else if (!preg_match('/[^a-zA-Z]+/',($_POST['repeat_password']))) {
                $register_error_message = 'Passwords must have at least one special character!';
                echo $register_error_message . "<br>";
            }
            else
            {
                try 
                {
                    // prepare sql and bind parameters
                    $stmt_password = $conn->prepare("UPDATE users SET password=:password  
                    WHERE id=:id");
                    $stmt_password->bindParam(':password', $encpassword);
                    $stmt_password->bindParam(':id', $id);
                    $stmt_password->execute();
		        }catch (PDOException $e) {
			        echo "error: " . $sql . "<br>" . $e->getMessage();
		        }
            }
        }
        $conn = null;
        header("Location: ./updatecheck.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Camagru - Home</title>
    <link rel="stylesheet" href="../css/gallery.css">
</head>
<body>
<ul>
        <li><a href="../camagru.php">Home</a></li>
        <li><a href="./gallery.php">Gallery</a></li>
        <li><a href="./logout.php">Logout</a></li>
</ul>
<div class="container">
    <div class="row">
            <h4>Update</h4>
            <form action="update.php" method="post">
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
                    <label for="">Email Notifications</label>
                    <select name="enote">
                        <option value="unchanged"></option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" name="btnUpdate" class="btn btn-primary" value="Update"/>
                </div>
            </form>
    </div>
</div>
 
</body>
</html>