<?php

    session_start();
    unset($_SESSION['name']);
    unset($_SESSION['status']);
    unset($_SESSION['email']);
    unset($_SESSION['gallery_start']);
    unset($_SESSION['gallery_end']);
    unset($_SESSION['gallery_count']);
    echo 'We have updated your profile, please sign in again using you new details</br>';
    echo '<a href="../index.php">Login</a>';

?>