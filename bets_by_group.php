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
</body>
</html>

<?php

session_start();
include "db_connect.php";
include "functions.php";

$user_data = check_login($mysqli);

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

$groupFromForm = $_GET["group_name"];

if($groupFromForm == "All"){
    header("Location:all_bets.php");
}else{
    include "group_select.php";

    echo "<br><h2>" . $groupFromForm . " open bets:</h2><br>";
    $sql = "SELECT id, bet, pro, con, prize, date, done FROM bets WHERE done=0 AND group_name='" . $groupFromForm . "' AND excluded NOT LIKE '%" . $_SESSION['user_id'] . "%' AND (" . $privateGroupsString . ")";
    $result = $mysqli->query($sql);

    if($result->num_rows > 0){
        echo "<table>";
        echo "<tr>";
        echo "<td>Bet</td><td>Pro</td><td>Con</td><td>Prize</td><td>Date</td><td>Close</td><td>Remove</td>";
        echo "</tr>";
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            echo "<td>" . $row["bet"] . "</td><td>" . $row["pro"] . "</td><td>" . $row["con"] . "</td><td>" . $row["prize"] . "</td><td>" . $row["date"] . "</td>
            <td>
                <form method='post' action=''>
                    <input type='submit' name='action' value='Close'/>
                    <input type='hidden' name='id' value='".$row[id]."'/>
                </form>
            </td>
            <td>
                <form method='post' action=''>
                    <input type='submit' name='action' value='Delete'/>
                    <input type='hidden' name='id' value='".$row[id]."'/>
                </form>
            </td>";
            echo "</tr>";
        }
        echo "</table>";
    }else{
        echo "no results";
    }

    //fetch finished bets
    echo "<br><h2>" . $groupFromForm . " closed bets:</h2><br>";
    $sql = "SELECT id, bet, pro, con, prize, date, done FROM bets WHERE done=1 AND group_name='" . $groupFromForm . "'  AND excluded NOT LIKE '%" . $_SESSION['user_id'] . "%' AND (" . $privateGroupsString . ")";
    $result = $mysqli->query($sql);

    if($result->num_rows > 0){
        echo "<table>";
        echo "<tr>";
        echo "<td>Bet</td><td>Pro</td><td>Con</td><td>Prize</td><td>Date</td><td>Open</td><td>Remove</td>";
        echo "</tr>";
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            echo "<td>" . $row["bet"] . "</td><td>" . $row["pro"] . "</td><td>" . $row["con"] . "</td><td>" . $row["prize"] . "</td><td>" . $row["date"] . "</td>
            <td>
                <form method='post' action=''>
                    <input type='submit' name='action' value='Open'/>
                    <input type='hidden' name='id' value='".$row[id]."'/>
                </form>
            </td>
            <td>
                <form method='post' action=''>
                    <input type='submit' name='action' value='Delete'/>
                    <input type='hidden' name='id' value='".$row[id]."'/>
                </form>
            </td>";
            echo "</tr>";
        }
        echo "</table>";
    }else{
        echo "no results";
    }

    if ($_POST['action'] && $_POST['id']) {
        if ($_POST['action'] == 'Close') {
            close_bet($_POST['id']);
            echo "<meta http-equiv='refresh' content='0'>";
        }
        if ($_POST['action'] == 'Open') {
            open_bet($_POST['id']);
            echo "<meta http-equiv='refresh' content='0'>";
        }
        if ($_POST['action'] == 'Delete') {
            delete_bet($_POST['id']);
            echo "<meta http-equiv='refresh' content='0'>";
        }
    }
}    
?>