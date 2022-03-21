<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        include "db_connect.php";
        $sql = "UPDATE user_log SET logout_time=UTC_TIMESTAMP() WHERE id='".$_SESSION['session_id']."'";
        $mysqli->query($sql);
        unset($_SESSION['user_id']);
    }

    header("Location: login.php");
    die;
?>