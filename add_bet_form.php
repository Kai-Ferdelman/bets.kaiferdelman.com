<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/menu_bar.css">
</head>
<body>

<?php

session_start();
include "db_connect.php";
include "functions.php";

$user_data = check_login($mysqli);
?>

<ul>
    <li><a href="index.php">Home</br></a></li>
    <li><a href="add_bet_form.php">Add Bet</a></li>
    <li><a href="add_group_form.php">Add group</a></li>
    <li><a href="bets_by_user.php">My bets</a></li>
    <li>
        <form action="bets_by_term.php">
            <div>
                <input type="text" name="search" placeholder="search term">
                <input type="submit" value="Search">
            </div>
        </form>
    </li>
    <li style="float: right;"><a href="logout.php" style="height:200%;">Logout</a></li>
    <li style="float: right;"><a href="profile.php" style="height:200%;">Profile</a></li>
</ul>

<form action="add_bet.php">
    <h2>Add a bet: </h2><br>
    <input type="text" name="bet" placeholder="Bet">
    <input type="text" name="pro" placeholder="Pro">
    <input type="text" name="con" placeholder="Con">
    <input type="text" name="prize" placeholder="Prize">
    <input type="date" name="date" placeholder="dd-mm-yyyy">
    <select name="group_name" id="groups">
        <?php
            //get private groups that don't include the user
            $sql = "SELECT group_name FROM groups WHERE private='1' AND members NOT LIKE '%".$_SESSION['user_id']."%'";
            $result = $mysqli->query($sql);
            $privateGroupsString = "group_name!= 'ALL'"; 
            if($result->num_rows > 0){
                $privateGroupsString .=" AND group_name!=";
                $row = $result->fetch_assoc();
                $privateGroupsString .= "'".$row['group_name']."'";
                while($row = $result->fetch_assoc()){
                    $privateGroupsString .= " AND group_name!=";
                    $privateGroupsString .= "'".$row['group_name']."'";
                }
            }

            $sql = "SELECT group_name FROM groups WHERE ".$privateGroupsString." ORDER BY group_name ASC";
            $results = $mysqli->query($sql);
            if($results->num_rows > 0){
                while($row = $results->fetch_assoc()){
                    echo "<option value='".$row['group_name']."'>".$row['group_name']."</option>";
                }
            }
        ?>
    </select>
    <label>Exclude:</label>
    <select name="excluded[]" multiple>
        <?php
            $sql = "SELECT username FROM users WHERE user_id!='".$_SESSION['user_id']."' ORDER BY username ASC";
            $results = $mysqli->query($sql);
            if($results->num_rows > 0){
                while($row = $results->fetch_assoc()){
                    echo "<option value='".$row['username']."'>".$row['username']."</option>";
                }
            }
        ?>
    </select>
    <input type="submit" value="Add">
</form>


</body>
</html>

