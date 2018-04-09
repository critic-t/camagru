<?php
error_reporting(0);
    include '../config/conn.php';

    session_start();
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $status = $_SESSION['status'];
    $overlay = $_SESSION['overlay'];

    define ('SITE_ROOT', realpath(dirname(__FILE__)));
    if($_GET['image_url'])
    {
        $image=$_GET['image_url'];
    }
    else
    {
        header("Location: ../index.php");
    }
    $image_url = $image;
    $string = $image_url;
    $string = explode('/', $string);
    $image_name = $string[2];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Camagru - Display Image</title>
        <link rel="stylesheet" href="../css/gallery.css">
    </head>
    <body>
    <ul>
        <li><a href="../camagru.php">Home</a></li>
        <li><a href="./inc/logout.php">Logout</a></li>
    </ul>
        <h1 align="center" style="margin: 0px auto;"><?php echo $image_name; ?></h1>
        <div  align="center" style="margin: 0px auto;">
            <?php
                echo '<img src="' . $image_url . '" width="400" height="400">';
            ?>
        </div>
        <div  align="center" style="margin: 0px auto;">    
            <?php

                if($email != "" && $status == "logged in")
                {
                    $stmt_photo = $conn->prepare("SELECT * FROM images WHERE image_name=:image_name");
                    $stmt_photo->bindValue(":image_name", $image_name);
                    if ($stmt_photo->execute()) {
                        //echo 'email: ';
                        while ($row = $stmt_photo->fetch(PDO::FETCH_ASSOC)) {
                            $image_creator_email = $row['image_creator_email'];
                            $image_likes = $row['image_likes'];
                            echo $image_creator_email;
                        }
                    }

                    echo
                        '
                            <form action="comment.php" id="commentform">
                                <textarea rows="4" cols="62" name="comment" form="commentform">Enter Comment Here...</textarea>
                                </br>
                                <input type="hidden" name="image_name" value=' . $image_name . ' />
                                <input type="submit" name="btnComment" class="btn btn-primary" value="Comment"/>
                            </form>
                        '
                    ;

                    echo
                        '
                            <form action="like.php" id="commentform">
                                <input type="hidden" name="image_name" value=' . $image_name . ' />
                                <input type="submit" name="btnLike" class="btn btn-primary" value="Like"/>
                            </form>
                        '
                    ;

                    //echo $image_creator_email;

                    if ($email == $image_creator_email){
                        echo    
                            '
                                <form action="delete.php" id="deleteform">
                                    <div class="form-group">
                                        <input type="hidden" name="image_name" value=' . $image_name . ' />
                                        <input type="submit" name="btnDelete" class="btn btn-primary" value="Delete"/>
                                    </div>
                                </form>
                            '
                        ;
                    }

                    echo    
                    '
                        <div>
                        <p>Likes: ' . $image_likes . ' </p>
                        </div>
                    '
                    ;

                    $stmt_comments = $conn->prepare("SELECT * FROM comments WHERE image_name=:image_name");
                    $stmt_comments->bindValue(":image_name", $image_name);
                    // initialise an array for the results 
                    $comments = array();
                    if ($stmt_comments->execute()) {
                        while ($row = $stmt_comments->fetch(PDO::FETCH_ASSOC)) {
                            $comments[] = $row;
                            $comment_creator = $row['comment_creator'];
                            $comment = $row['comment'];

                            echo    
                            '
                                <div class="comments">
                                    <h4> ' . $comment_creator . '  </h4>
                                    <p> ' . $comment . ' </p></br>
                                </div>
                            '
                        ;

                        }
                    }

                }

            ?>
        </div>
    </body>
</html>