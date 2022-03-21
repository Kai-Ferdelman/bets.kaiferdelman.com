<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/menu_bar.css">
<link rel="stylesheet" href="css/tables.css">
</head>
<body>

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

<?php
    session_start();
    include "db_connect.php";
    include "functions.php";

    $user_data = check_login($mysqli);

    header("Location:all_bets.php");
    $mysqli->close();
?>

</body>
</html>