<?php
include SITE_ROOT . '/config/conn.php';

$name = $_SESSION['name'];
$email = $_SESSION['email'];

if(isset($_FILES["fileToUpload"])){
    $expensions= array("jpeg","jpg","png");
    

    $target_dir = "/images/assets/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            list($width_a, $height_a) = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($width_a != 400 && $height_a != 300)
            {
                echo "File has to be 400px x 300px";
                $uploadOk = 0;
            } else {
                $uploadOk = 1;
            }
        } else {
            //echo "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists. <br>";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large." . "<br>";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Sorry, only JPG, JPEG & PNG files are allowed.<br>";
        $_SESSION['imageFileType'] = $imageFileType;
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.<br>";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], SITE_ROOT . $target_file)) {
            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.<br>";
            $naming =  $_FILES["fileToUpload"]["name"];
            echo $naming . "<br>";
            
            $upload_fir = SITE_ROOT . "/images/";         
            $upload_dir = SITE_ROOT . "/images/assets/";
            $file = $upload_dir . $naming;
            $file2 = $upload_dir . "overlay.png";     
            
             // Get dimensions for specified images
            
             list($width_x, $height_x) = getimagesize($file);
             list($width_y, $height_y) = getimagesize($file2);
            
             // Create new image with desired dimensions
            
             $image = imagecreatetruecolor($width_y, $height_y);
            
             // Load images and then copy to destination image
            
             if($imageFileType == "png") {
                $image_x = imagecreatefrompng($file);
             } else if($imageFileType == "jpg") {
                $image_x = imagecreatefromjpg($file);
             } else if($imageFileType == "jpeg") {
                $image_x = imagecreatefromjpeg($file);
             }
             $image_y = imagecreatefrompng($file2);
            
             imagecopy($image, $image_x, 0, 0, 0, 0, $width_x, $height_x);
             imagecopy($image, $image_y, 0, 0, 0, 0, $width_y, $height_y);
            
             // Save the resulting image to disk (as png)
             $image_created = mktime();
             $image_creator = $name;
             $image_name = $name . '-' . mktime() . '.png';
            
             imagepng($image, $upload_dir . 'output.png');
             imagepng($image, $upload_fir . $name . '-' . mktime() . '.png');
             
             // Clean up
             imagedestroy($image);
             imagedestroy($image_x);
             imagedestroy($image_y);
            
             try {
                // prepare sql and bind parameters
                $stmt = $conn->prepare("INSERT INTO images(image_creator, image_creator_email, image_url) 
                VALUES(:image_creator, :image_creator_email, :image_url)");
                $stmt->bindParam(':image_creator', $name);
                $stmt->bindParam(':image_creator_email', $email);
                $stmt->bindParam(':image_url', $image_name);
                $stmt->execute();
            } catch (PDOException $e) {
                echo "error: " . $sql . "<br>" . $e->getMessage();
            }
            $conn = null;

        } else {
            echo "Sorry, there was an error uploading your file.<br>";
        }
    }
}
?>