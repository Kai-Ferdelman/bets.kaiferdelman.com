<?php
    function check_login($mysqli){
        if(isset($_SESSION['user_id'])){
            $id = $_SESSION['user_id'];
            $sql = "SELECT * FROM users WHERE user_id = '$id' LIMIT 1";

            $result = $mysqli->query($sql);
            if($result->num_rows > 0){
                $user_data = $result->fetch_assoc();
                return $user_data;
            }
        }
        //redirect to login
        header("Location: login.php");
        die;
    }

    function random_num($length){
        $text = "";
        if($length < 5){
            $length = 5;
        }

        $len = random_int(4, $length);

        for($i = 0; $i < $len; $i++){
            $text .= random_int(0,9);
        }

        return $text;
    }

    function close_bet($bet_id){
        include "db_connect.php";
        $sql = "UPDATE bets SET done=1 WHERE id='".$bet_id."'";
        $mysqli->query($sql);
    }

    function open_bet($bet_id){
        include "db_connect.php";
        $sql = "UPDATE bets SET done=0 WHERE id='".$bet_id."'";
        $mysqli->query($sql);
    }

    function delete_bet($bet_id){
        include "db_connect.php";
        $sql = "Delete FROM bets WHERE id='".$bet_id."'";
        $mysqli->query($sql);
    }
?>