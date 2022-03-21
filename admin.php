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
    if($user_data['admin'] == 0){
        header("Location: profile.php");
    }

    echo "<h1 style='line-height: 0px;'>" . $_SESSION['username'] . "'s Admin Panel</h1><br>";
?>

<h2>Signup</h2>
<form method="post">
    <input type="text" name="username" placeholder="username"><br>
    <input type="password" name="password" placeholder="password"><br>
    <input type="submit" value="Sign up"><br>
</form>

<?php
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(!empty($username) && !empty($password) && !is_numeric($username)){
        $sql = "SELECT * FROM users WHERE username='".$_POST['username']."' LIMIT 1";
        $result = $mysqli->query($sql);
        if ($result->num_rows == 0) {
            $user_id = random_num(20);
            $password_hash = hash('sha256', $password);
            $sql = "INSERT INTO users (user_id, username, password) VALUES ('$user_id', '$username', '$password_hash')";
            $mysqli->query($sql);
            echo "User added";
        }else{
            echo "Username is already taken";
        }
    }else{
        echo "Enter valid Username and Password";
    }
}
?>

<h2>Change admin rights</h2>
<form method='post' admin_username='' admin_effect=''>
    <select name="admin_username">
        <?php
            $sql = "SELECT username FROM users WHERE user_id!='2222802' ORDER by username ASC";
            $results = $mysqli->query($sql);
            if($results->num_rows > 0){
                while($row = $results->fetch_assoc()){
                    echo "<option admin_username='".$row['username']."'>".$row['username']."</option>";
                }
            }
        ?>
    </select>
    <select name="admin_effect">
            <option admin_effect="Take" >Take</option>
            <option admin_effect="Give" >Give</option>
    </select><br>
    <input type='submit' value='Change'>
</form>

<?php
if ($_POST['admin_username'] && $_POST['admin_effect']) {
    if($_POST['admin_effect'] == "Take"){
        echo "Took admin rights from " . $_POST['admin_username'];
        $sql = "UPDATE users SET admin='0' WHERE username='".$_POST['admin_username']."'";
        $mysqli->query($sql);
    }else if($_POST['admin_effect'] == "Give"){
        echo "Gave admin rights to " . $_POST['admin_username'];
        $sql = "UPDATE users SET admin='1' WHERE username='".$_POST['admin_username']."'";
        $mysqli->query($sql);
    }
}
?>