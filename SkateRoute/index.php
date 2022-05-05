<?php
require_once('Includes/connection.php');
session_start();
session_destroy();

$username = $password = $accountErr = "";
$method = $_SERVER["REQUEST_METHOD"];

$safepost = filter_input_array(INPUT_POST, [
    "username" => FILTER_SANITIZE_STRING,
    "password" => FILTER_SANITIZE_STRING,
        ]);

if ($method == "POST") {
    if (empty($safepost["username"])) {
        $invalid = true;
    } else {
        $username = test_input($safepost["username"]);
        if (!preg_match("/^[a-zA-Z]*$/", $username)) {
            $invalid = true;
        }
    }
}

if ($method == "POST") {
    if (empty($safepost["password"])) {
        $invalid = true;
    } else {
        $password = test_input($safepost["password"]);
        if (!preg_match("/^[a-zA-Z]*$/", $password)) {
            $invalid = true;
        }
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['submit'])) {
    $login = $con->prepare("
                    SELECT username, password, id
                    FROM user
                    WHERE username = :username
                ");

    $loginsuccess = $login->execute([
        'username' => $username
    ]);

    if ($loginsuccess) {
        $count = $login->rowCount();
        if ($count == 1) {
            $user = $login->fetch(PDO::FETCH_OBJ);
            $dbpassword = $user->password;
            if (password_verify($password, $dbpassword)) {
                $userid = $user->id;
                session_start();
                $_SESSION["id"] = $userid;
//                $_SESSION["username"] = strtolower($username);
//                $_SESSION["password"] = strtolower($dbpassword);
                session_write_close();
                header("location: Home.php");
            }
        }
        $accountErr = "Username or password is incorrect.";
        $invalid = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <title>Skate Route</title>
        <link rel="stylesheet" href="css/Global.css">
        <link rel="stylesheet" href="css/LoginRegister.css">
        <link rel="stylesheet" href="css/Login.css">
        <!--<script type="text/javascript" src="js/SkateRoute.js"></script>-->
    </head>

    <body>
        <div class="container">
            <div class="content">
                <div class="brand">
                    <img src="Assets/Images/logo.png" alt="">
                    <h1>SkateRoute</h1>
                </div>
                <div class="userinput">
                    <!--                    <form class="" action="index.html" method="post">
                                            <input id="username" type="text" name="username" value="" placeholder="Username" required>
                                            <input id="password" type="password" name="password" value="" placeholder="Password" required>
                                            <a id="forgotten" href="#">Forgotten your password?</a>
                                            <button type="button" name="button" onclick="login()" required>Login</button>
                                            <button type="button" name="button" onclick="window.location='Register.php'">Register</button>
                                        </form>-->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <input type="text" name="username" value="<?php echo $username; ?>" placeholder="Username" required/>
                        <input type="password" name="password" value="<?php echo $password; ?>" placeholder="Password" required/>
                        <input id="submit" type="submit" name="submit" value="Login">
                        <button type="button" onclick="window.location = 'Register.php'">Register</button>
                        <span class="error"><?php echo $accountErr; ?></span>
                    </form>
                </div>
            </div>
        </div> 
    </body>

</html>