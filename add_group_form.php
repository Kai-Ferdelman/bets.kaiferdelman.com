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

<form action="add_group.php">
    <h2>Add a Group: </h2><br>
    <input type="text" name="group_name" placeholder="Group Name">
    <select name="members[]" id="groups" multiple>
        <?php
            include "db_connect.php";
            $sql = "SELECT username FROM users WHERE user_id!='".$_SESSION['user_id']."' ORDER BY username ASC";
            $results = $mysqli->query($sql);
            if($results->num_rows > 0){
                while($row = $results->fetch_assoc()){
                    echo "<option value='".$row['username']."'>".$row['username']."</option>";
                }
            }
        ?>
    </select>
    <input type="hidden" name="private" value="0">
    <input type="checkbox" name="private" value="1">
    <label>Private Group</label><br>
    <input type="submit" value="Add">
</form>


</body>
</html>