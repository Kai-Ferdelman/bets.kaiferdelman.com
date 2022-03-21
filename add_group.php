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

    session_start();
    include "db_connect.php";
    include "functions.php";

    $user_data = check_login($mysqli);

    $group_nameFromForm = $_GET["group_name"];
    $membersFromForm = $_GET["members"];
    $privateFromForm = $_GET["private"];

    if($group_nameFromForm){
        $sql = "SELECT * FROM groups WHERE group_name='".$group_nameFromForm."' LIMIT 1";
        $result = $mysqli->query($sql);
        if($result->num_rows == 0){
            $membersString = $_SESSION['user_id'] . ",";
            $sql = "SELECT group_association FROM users WHERE user_id='".$_SESSION['user_id']."' LIMIT 1";
            $result = $mysqli->query($sql);
            $row = $result->fetch_assoc();

            $group_association = $row["group_association"];
            $group_association .= $group_nameFromForm . ",";
            
            $sql = "UPDATE users SET group_association='".$group_association."' WHERE user_id='".$_SESSION['user_id']."'";
            $mysqli->query($sql);


            foreach($membersFromForm as $value){
                $sql = "SELECT user_id, group_association FROM users WHERE username='".$value."' LIMIT 1";
                $result = $mysqli->query($sql);
                $row = $result->fetch_assoc();
                $membersString .= $row['user_id'] . ",";

                $group_association = $row["group_association"];
                $group_association .= $group_nameFromForm . ",";

                $sql = "UPDATE users SET group_association='".$group_association."' WHERE username='".$value."'";
                $mysqli->query($sql);
            }

            $sql = "INSERT INTO groups (id, group_name, members, private) VALUES (NULL, '$group_nameFromForm', '$membersString', '$privateFromForm')";
            $result = $mysqli->query($sql);
            echo "<h1>Your group has been created</h1>";
            echo "<a href='index.php'>Return</a>";
        }else{
            echo "<h1>This group already exists</h1>";
            echo "<a href='index.php'>Return</a>";
        }
    }else{
        echo "<h2>No group name given!</h2><br>";
        echo "<a href='add_group_form.php'>Return</a>";
    }

?>