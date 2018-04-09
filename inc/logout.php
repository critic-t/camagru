<?php

session_start();
unset($_SESSION['name']);
unset($_SESSION['status']);
unset($_SESSION['email']);
unset($_SESSION['gallery_start']);
unset($_SESSION['gallery_end']);
unset($_SESSION['gallery_count']);
header("Location: ../index.php");
exit();

?>