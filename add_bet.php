<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/menu_bar.css">
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

</body>
</html>

<?php
    include "db_connect.php";
    $betFromForm = $_GET["bet"];
    $proFromForm = $_GET["pro"];
    $conFromForm = $_GET["con"];
    $prizeFromForm = $_GET["prize"];
    $dateFromForm = $_GET["date"];
    $groupFromForm = $_GET["group_name"];
    $excludedFromForm = $_GET["excluded"];

    if($betFromForm && $proFromForm && $conFromForm && $prizeFromForm && $groupFromForm){
        $excludedString = "";

        foreach($excludedFromForm as $value){
            $sql = "SELECT user_id FROM users WHERE username='".$value."'";
            $result = $mysqli->query($sql);
            $row = $result->fetch_assoc();
            $excludedString .= $row['user_id'] . ",";
        }

        $sql = "INSERT INTO bets (id, bet, pro, con, prize, date, group_name, excluded, done) VALUES (NULL, '$betFromForm', '$proFromForm', '$conFromForm', '$prizeFromForm', '$dateFromForm', '$groupFromForm', '$excludedString', 0)";
        $result = $mysqli->query($sql);
        echo "<h1>Your bet has been added</h1>";
        echo "<a href='index.php'>Return</a>";
    }else{
        
        echo "<h2>One of the required fields was not filled in!</h2>";
        echo "<a href='add_bet_form.php'>Return</a>";
    }
?>