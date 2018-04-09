<?php

// Start Session
session_start();

include '../config/conn.php';

$name = $_SESSION['name'];
$email = $_SESSION['email'];

$upload_fir = "../images/";
$upload_dir = "../images/assets/";
$img = $_POST['hidden_data'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$file = $upload_dir . 'input' . ".png";
$success = file_put_contents($file, $data);
print $success ? $file : 'Unable to save the file.';

$file2 = $upload_dir . "overlay.png";     

 // Get dimensions for specified images

 list($width_x, $height_x) = getimagesize($file);
 list($width_y, $height_y) = getimagesize($file2);

 // Create new image with desired dimensions

 $image = imagecreatetruecolor($width_x, $height_x);

 // Load images and then copy to destination image

 $image_x = imagecreatefrompng($file);
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
    $stmt = $conn->prepare("INSERT INTO images(image_name, image_creator, image_creator_email, image_url) 
    VALUES(:image_name, :image_creator, :image_creator_email, :image_url)");
    $stmt->bindParam(':image_name', $image_name);
    $stmt->bindParam(':image_creator', $name);
    $stmt->bindParam(':image_creator_email', $email);
    $stmt->bindParam(':image_url', $image_name);
    $stmt->execute();
} catch (PDOException $e) {
    echo "error: " . $sql . "<br>" . $e->getMessage();
}
$conn = null;

?>