<?php

header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache'); 

$name = $_SESSION['name'];
$email = $_SESSION['email'];
$status = $_SESSION['status'];
$overlay = $_SESSION['overlay'];

if($email == "" && $status != "logged in")
{
    header("Location: index.php");
}

if (isset($_GET['switchoverlay'])) {

    $overlay = $_SESSION['overlay'];

    $upload_dir = "./images/assets/";
    if ($overlay < 3) {
        $overlay = $overlay + 1;
        $_SESSION['overlay'] = $overlay;
    } else {
        $overlay = 1;
        $_SESSION['overlay'] = $overlay;
    }
    
    if ($overlay == 1) {
        $file = $upload_dir . "blank.png";   
    } else if ($overlay == 2) {
        $file = $upload_dir . "hat2.png";
    } else if ($overlay == 3) {
        $file = $upload_dir . "hat3.png";
    } else {
        $file = $upload_dir . "hat1.png";
    }
    
     // Get dimensions for specified images
    
     list($width_x, $height_x) = getimagesize($file);
    
     // Load images and then copy to destination image
     $image_x = imagecreatefrompng($file);

     // Create new image with desired dimensions
     $image = imagecreatetruecolor($width_x, $height_x);
     imagealphablending($image, false);
     imagesavealpha( $image, true );

     imagecopyresampled($image, $image_x, 0, 0, 0, 0, $width_x, $height_x, $width_x, $height_x);
     imagepng($image, $upload_dir . 'overlay.png', 9);
     // Clean up
     imagedestroy($image);
     imagedestroy($image_x);

}

?>