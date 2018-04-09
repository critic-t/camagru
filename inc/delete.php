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

    if($_GET['image_name'])
    {
        $image_name = $_GET['image_name'];
        $image_url = "../images/" . $image_name;
        $stmt = $conn->prepare("DELETE FROM images WHERE image_name=:image_name");
        $stmt->bindParam(':image_name', $image_name);
        if (unlink($image_url))
        {
            $stmt->execute();
            echo $image_name . " deleted!";
        }
        else
        {
            echo "Error deleting " . $image_name;
        }
    }
    else
    {
        header("Location: ../index.php");
    }

?>