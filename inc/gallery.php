<!DOCTYPE html
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Camagru - Gallery</title>
        <link rel="stylesheet" href="../css/gallery.css">
    </head>
    <body>
    <ul>
        <li><a href="../camagru.php">Home</a></li>
        <li><a href="./logout.php">Logout</a></li>
    </ul>
        <h1 align="center" style="margin: 0px auto;">Gallery</h1>
        <table align="center" style="margin: 0px auto;">
            <?php
            error_reporting(0);
            session_start();
            $condition = $_GET['condition'];
            if ($condition == 'next') {
                $rows_per_page = 15;
                $_SESSION['gallery_count'] =  $_SESSION['gallery_count'] + 1;
                $start = $_SESSION['gallery_start'];
                $end = $_SESSION['gallery_end'];
                $count = $_SESSION['gallery_count'];
            }
                $x = 0;
                $files = glob("../images/*.png");
                usort($files, create_function('$b,$a', 'return filemtime($a) - filemtime($b);'));
                $file_count  = count($files);
                for ($i = 0; $i < 15; $i++) 
                {
      
                    $image = $files[$start];
                    $image_url = $image;
                    if($x % 5 == 0) {
                        echo "<tr>";
                    }
                    if ($image_url != ""){
                        echo '<td><a href="../inc/display.php?image_url=' . $image_url . '"><img src="../images/' . $image_url . '" width="200" height="200"></a></td>';
                    }
                    if($x % 5 == 4) {
                        echo "</tr>";
                    }
                    $start++;
                    $x++;
                }
                $_SESSION['gallery_start'] = $start + $rows_per_page;
                $_SESSION['gallery_end'] = ($file_count / $rows_per_page) / 2;
            ?>

        </table>
        <div align="center" style="margin: 0px auto;">
        <?php

            echo '<br>';
            if ($count > 1) {
                //echo '<a id="back" href="./galleria.php?condition=back" align="center"> <- Back </a>';
            }
            echo '&nbsp';
            if ($count <= $_SESSION['gallery_end']) {
                echo '<a href="./galleria.php?condition=next" align="center"> Next -> </a>';
            }else{
                echo '<button onclick="goBack()">Go Back</button>';
            }
        ?>
        </div>

        <script>
            function goBack() {
                window.history.back();
            }
        </script>

        <script src="../js/back.js"></script>
    </body>
</html>