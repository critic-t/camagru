<?php

// Start Session
    session_start();
    define ('SITE_ROOT', realpath(dirname(__FILE__)));

    include SITE_ROOT . '/inc/uploadphoto.php';
    include './inc/overlay.php';
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Camagru - Home</title>
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body>
    <ul>
        <li><a href="./">Home</a></li>
        <li><a href="./inc/gallery.php">Gallery</a></li>
        <li><a href="./inc/update.php">Update Profile</a></li>
        <li><a href="./inc/logout.php">Logout</a></li>
    </ul>
        <div id="main_section" align="center">
            <div class="booth_container">
                <div class="booth">
                    <div id="booth_footage">
                        <div id="video_overlays"><img id="vid_overlay" width="400" height="300" src="./images/assets/overlay.png?v=<?php echo Date("Y.m.d.G.i.s")?>" alt="frame"></div>
                            <video id="video" width="400" height="300"></video>
                        </div>
                    </div>

                    <img id="photo" width="400" height="300" src="./images/assets/output.png?v=<?php echo Date("Y.m.d.G.i.s")?>" alt="WearIt">
                    
                    <nav id="navigate">
                        <ul class="images_scroll">
                            <?php
                                include './config/conn.php';

                                $path = "./images/";

                                $stmt = $conn->prepare("SELECT * FROM images WHERE image_creator=:image_creator ORDER BY image_timestamp DESC");
                                $stmt->bindValue(":image_creator", $name);
                                // initialise an array for the results 
                                $user_images = array();
                                if ($stmt->execute()) {
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $user_images[] = $row;
                                        $image_url = $row['image_url'];
                                        $url = $path . $image_url;
                                        echo '<li><img src="./images/' . $image_url . '" width="150" height="150"></li>';
                                    }
                                }
                                // set PDO to null in order to close the connection
                                $conn = null;
                            ?>
                        </ul>
                    </nav>

                    <canvas id="canvas" width="400" height="300"></canvas>
                </div>
            </div>

            <div class="button" align="center">
                <a href="#" id="capture" class="booth-capture-button">Wear IT!</a>
                <a href="?switchoverlay" id="switch" class="booth-capture-button">Change Overlay</a>
            </div>
        
            <form method="post" accept-charset="utf-8" name="form1">
                <input name="hidden_data" id='hidden_data' type="hidden"/>
            </form>

            <form action="" align="center" method="POST" enctype="multipart/form-data">
                <input type="file" name="fileToUpload" />
                <input type="submit"/>
            </form>
            
            <div class="button" align="center">
                <a href="?switchoverlay" id="hat3" class="booth-capture-button">Hat 3</a>
                <a href="?switchoverlay" id="hat2" class="booth-capture-button">Hat 2</a>
                <a href="?switchoverlay" id="hat1" class="booth-capture-button">Hat 1</a>
            </div>
            
            <script src="js/photo.js"></script>
            <script src="js/reload.js"></script>
            <script src="js/switchoverlay.js"></script>
            
        </div>
        <FOOTER align="center">
		<p1>TRATEFANE 2018</p1>
		</FOOTER>
    </body>
</html>