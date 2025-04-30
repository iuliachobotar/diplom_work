<?php
session_start();
session_unset();
session_destroy();

include 'connect.php';

setcookie('user_id', '', time() - 1, '/');

header('Location: /home.php');

?>
