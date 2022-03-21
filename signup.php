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

<h1>Sign up</h1>
<form method="post">
    <input type="text" name="username" placeholder="username"><br>
    <input type="password" name="password" placeholder="password"><br>
    <input type="submit" value="Sign up"><br>
</form>

</body>
</html>

<?php
    session_start();
    include "db_connect.php";
    include "functions.php";

    $user_data = check_login($mysqli);
    if($user_data['admin'] != 1){
        header("Location:profile.php");
    }

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
                header("Location: signup.php");
            }else{
                echo "Username is already taken";
            }
        }else{
            echo "Enter valid Username and Password";
        }
    }
?>