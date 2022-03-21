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

    echo "<h1 style='line-height: 0px;'>Welcome " . $_SESSION['username'] . "</h1><br>";

    //group information
    echo "<h2 style='line-height: 0px;'>You are part of the following groups:</h2><br>";
    
    $sql = "SELECT group_association from users WHERE user_id='".$_SESSION['user_id']."'";
    $result = $mysqli->query($sql);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $groups = explode(",", $row['group_association']);
        foreach($groups as $value){
            echo $value . "<br>";
        }
    }else{
        echo "You are currently not part of any groups!";
    }
    
    //change username
    echo "<h2 style='line-height: 0px;'>Change Username</h2><br>";
    echo "
        <form method='post' username=''>
            <input type='text' name='username' placeholder='New Name'><br>
            <input type='submit' value='Change'>
        </form>";
    
    if ($_POST['username']) {
        //check that username is not taken
        $sql = "SELECT * FROM users WHERE username='".$_POST['username']."' LIMIT 1";
        $result = $mysqli->query($sql);
        if ($result->num_rows == 0) {
            //change username
            $sql = "UPDATE users SET username='".$_POST['username']."' WHERE user_id='".$_SESSION['user_id']."'";
            $mysqli->query($sql);
            $_SESSION['username'] = $_POST['username'];
            echo "<meta http-equiv='refresh' content='0'>";
        }else{
            echo "Username is already taken!";
        }
    }
    echo "<br>";

    //change password
    echo "<h2 style='line-height: 0px;'>Change Password</h2><br>";
    echo "
        <form method='post' password='' new_password='' new_password_conf=''>
            <input type='password' name='password' placeholder='Old Password'><br><br>
            <input type='password' name='new_password' placeholder='New Password'><br>
            <input type='password' name='new_password_conf' placeholder='Confirm Password'><br>
            <input type='submit' value='Change'>
        </form>";

    if ($_POST['password'] && $_POST['new_password'] && $_POST['new_password_conf']) {
        $sql = "SELECT * FROM users WHERE user_id='".$_SESSION['user_id']."' LIMIT 1";
        $result = $mysqli->query($sql);
        $row = $result->fetch_assoc();
        if ($result->num_rows > 0) {
            //check that password is correct
            $password_hash = hash('sha256', $_POST['password']);
            if($password_hash == $row['password']){
                //chack if new passwords are same
                if($_POST['new_password'] == $_POST['new_password_conf']){
                    $new_password_hash = hash('sha256', $_POST['new_password']);

                    //change password
                    $sql = "UPDATE users SET password='".$new_password_hash."' WHERE user_id='".$_SESSION['user_id']."'";
                    $mysqli->query($sql);
                    echo "<meta http-equiv='refresh' content='0'>";
                }else{
                    echo "New password does not match the confirmation!";
                }
            }else{
                echo "Incorrect password!";
            }
        }
    }

    //signup function
    echo "<h2>Admin</h2>";
    if($user_data['admin'] == 1){
        echo "<p>Continue to the admin panel <a href='admin.php'>here</a>.</p>";
    }else{
        echo "You currently do not have admin rights. If you believe this to be a mistake, please contact an admin.";
    }
    

?>

