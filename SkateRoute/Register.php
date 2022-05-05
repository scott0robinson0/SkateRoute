<?php
require_once('Includes/connection.php');

$firstname = $lastname = $email = $username = $password = $firstnameErr = $lastnameErr = $emailErr = $usernameErr = $passwordErr = "";

$method = $_SERVER["REQUEST_METHOD"];

$safepost = filter_input_array(INPUT_POST, [
    "firstname" => FILTER_SANITIZE_STRING,
    "lastname" => FILTER_SANITIZE_STRING,
    "email" => FILTER_SANITIZE_EMAIL,
    "username" => FILTER_SANITIZE_STRING,
    "password" => FILTER_SANITIZE_STRING
        ]);

$invalid = false;

if ($method == "POST") {
    if (empty($safepost["firstname"])) {
        $firstnameErr = "First name is required";
        $invalid = true;
    } else {
        $firstname = test_input($safepost["firstname"]);
        if (!preg_match("/^[a-zA-Z]*$/", $firstname)) {
            $firstnameErr = "Only letters and white space allowed";
            $invalid = true;
        }
    }
}

if ($method == "POST") {
    if (empty($safepost["lastname"])) {
        $lastnameErr = "Last name is required";
        $invalid = true;
    } else {
        $lastname = test_input($safepost["lastname"]);
        if (!preg_match("/^[a-zA-Z]*$/", $lastname)) {
            $lastnameErr = "Only letters and white space allowed";
            $invalid = true;
        }
    }
}

if ($method == "POST") {
    if (empty($safepost["email"])) {
        $emailErr = "Email is required";
        $invalid = true;
    } else {
        $email = test_input($safepost["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
            $invalid = true;
        } elseif (!preg_match("/^[a-zA-Z1-9@.]*$/", $email)) {
            $emailErr = "Invalid characters used";
        }
    }
}

if ($method == "POST") {
    if (empty($safepost["username"])) {
        $usernameErr = "Username is required";
        $invalid = true;
    } else {
        $username = test_input($safepost["username"]);
        if (!preg_match("/^[a-zA-Z1-9]*$/", $username)) {
            $usernameErr = "Invalid characters used";
            $invalid = true;
        }
    }
}

if ($method == "POST") {
    if (empty($safepost["password"])) {
        $passwordErr = "Password is required";
        $invalid = true;
    } elseif (strlen($safepost["password"]) < 8) {
        $passwordErr = "Password must be at least 8 characters long";
        $password = $safepost["password"];
        $invalid = true;
    } else {
        $password = test_input($safepost["password"]);
        if (!preg_match("/^[a-zA-Z1-9]*$/", $password)) {
            $passwordErr = "Invalid characters used";
            $invalid = true;
        }
    }
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['submit'])) {
    $selectusername = $con->prepare("
                    SELECT *
                    FROM user
                    WHERE username = :username
                ");

    $selectusernamesuccess = $selectusername->execute([
        'username' => $username,
    ]);

    if ($selectusernamesuccess) {
        $count = $selectusername->rowCount();
        if ($count >= 1) {
            $usernameErr = "Username already exists";
            $invalid = true;
        }
    }

    $selectemail = $con->prepare("
                    SELECT *
                    FROM user
                    WHERE email = :email
                ");

    $selectemailsuccess = $selectemail->execute([
        'email' => $email,
    ]);

    if ($selectemailsuccess) {
        $count = $selectemail->rowCount();
        if ($count >= 1) {
            $emailErr = "Email already exists";
            $invalid = true;
        }
    }

    if ($invalid == false) {
        $createaccount = $con->prepare("
                    INSERT INTO user (email, username, password, firstname, lastname, profilepicture)
                    VALUES (:email, :username, :password, :firstname, :lastname, :image)
                ");
        
        $file = file_get_contents($_FILES['upload']['tmp_name']);
        $createaccountsuccess = $createaccount->execute([
            'email' => strtolower($email),
            'username' => strtolower($username),
            'password' => $hashed_password,
            'firstname' => strtolower($firstname),
            'lastname' => strtolower($lastname),
            'image' => $file
        ]);

        $account = $createaccount->fetch(PDO::FETCH_OBJ);
        
//        session_start();
//        $_SESSION["email"] = $account->email;
//        $_SESSION["firstname"] = $account->firstname;
//        $_SESSION["lastname"] = $account->lastname;
//        $_SESSION["username"] = $account->username;
//        $_SESSION["password"] = $account->password;
//        session_write_close();
        header("Location: index.php");
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
        <link rel="stylesheet" href="css/Register.css">
        <link rel="stylesheet" href="css/LoginRegister.css">
    </head>

    <body>
        <div class="container">
            <div class="content">
                <div class="brand">
                    <img src="Assets/Images/logo.png" alt="">
                    <h1>SkateRoute</h1>
                </div>
                <div class="userinput">
                    <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">

                        <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="First Name" required/>
                        <span class="error">* <?php echo $firstnameErr; ?></span>

                        <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="Last Name" required/>
                        <span class="error">* <?php echo $lastnameErr; ?></span>

                        <input type="text" name="email" value="<?php echo $email; ?>" placeholder="Email" required/>
                        <span class="error">* <?php echo $emailErr; ?></span>

                        <input type="text" name="username" value="<?php echo $username; ?>" placeholder="Username" required/>
                        <span class="error">* <?php echo $usernameErr; ?></span>

                        <input type="password" name="password" value="<?php echo $password; ?>" placeholder="Password" required/>
                        <span class="error">* <?php echo $passwordErr; ?></span>
                        
                        <input type="file" name="upload" required/>

                        <input id="submit" type="submit" name="submit" value="Register">
                        <button type="button" onclick="window.location = 'index.php'">Login</button>
                    </form>
                </div>
            </div>
        </div>
        <!--<script type="text/javascript" src="js/SkateRoute.js"></script>-->
    </body>

</html>
