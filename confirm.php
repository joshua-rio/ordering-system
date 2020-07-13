<?php
    session_start();
    include "connection.php";
	include "classes.php";
    $username = $_POST['username'];
    $type = $_POST['type'];

    $AdminInSession = new Admin($username, $type);
    $_SESSION['AdminSession'] = serialize($AdminInSession);
?>