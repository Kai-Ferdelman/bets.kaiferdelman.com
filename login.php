<style>
body {
  background-image: url('img/background.png');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: cover;
}
#box {
    margin: auto;
    width: 10%;
    padding: 20px;
}
#vertical-align{
    /* background-color: grey; */
    margin:0;
    position: absolute;
    top: 50%;
    -ms-transform: translateY(-50%);
    transform: translateY(-50%);
}
#login{

    margin: auto;
    width: 50%;
    padding: 20px;
    color: white;
    text-shadow:
    -1px -1px 0 #000,
    1px -1px 0 #000,
    -1px 1px 0 #000,
    1px 1px 0 #000;  
}
#input{
    padding:10px;
}
#submit{
    margin: auto;
    width: 50%;
}
</style>


<?php
    session_start();
    include "db_connect.php";
    include "functions.php";

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $username = $_POST['username'];
        $password = $_POST['password'];

        if(!empty($username) && !empty($password) && !is_numeric($username)){
            $sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
            $result = $mysqli->query($sql);

            $sql = "INSERT INTO user_log (id, username, success, login_time) VALUES (NULL, '".$username."', '0', UTC_TIMESTAMP())";
            $mysqli->query($sql);
            $sql = "SELECT MAX(id) FROM user_log";
            $result_id = $mysqli->query($sql);
            if($result_id->num_rows > 0){
                $row = $result_id->fetch_assoc();
                $_SESSION['session_id'] = $row['MAX(id)'];
            }

            if($result->num_rows > 0){
                $user_data = $result->fetch_assoc();
                $password_hash = hash('sha256', $password);

                if($user_data['password'] == $password_hash){
                    //successful login
                    $_SESSION['user_id'] = $user_data['user_id'];
                    $_SESSION['username'] = $username;

                    $sql = "UPDATE user_log SET success='1', user_id='".$_SESSION['user_id']."' WHERE id='".$_SESSION['session_id']."'";
                    $mysqli->query($sql);
                    header("Location: index.php");
                    die;
                }
            }
            echo "Incorrect Username or Password";
        }
    }
?>

<div id="box">
    <div id="vertical-align">
        <h1 id="login">Login</h1>
        <form method="post">
            <input id="input" type="text" name="username" placeholder="username"><br>
            <input id="input" type="password" name="password" placeholder="password"><br>
            <div id="submit"><input type="submit" value="Login" style="width:100%"></div><br>
        </form>
    </div>
</div>