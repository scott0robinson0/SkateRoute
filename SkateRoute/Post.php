<?php
require 'Includes/connection.php';

//session_start();

$userid = 4;

$getfollowing = $con->prepare("
        SELECT following
        FROM follow
        WHERE follower = :userid
    ");
$getfollowing->bindParam(":userid", $userid, PDO::PARAM_INT);
$getfollowing->execute();
$following = $getfollowing->fetchAll(PDO::FETCH_COLUMN);

$qMarks = str_repeat('?,', count($following) - 1) . '?';

$getpost = $con->prepare("
        SELECT post.caption, post.datecreated, post.timecreated, post.likes, user.username, post.image, user.profilepicture
        FROM post 
        INNER JOIN user on post.user_id = user.id 
        WHERE post.user_id IN ($qMarks)
    ");
$getpost->execute($following);
$posts = $getpost->fetchAll();
?>
<html>
    <head>
    <script src="js/Post.js"></script>
    <link rel="stylesheet" href="css/Global.css">
    <link rel="stylesheet" href="css/Home.css">
    </head>
    
    <body style="background-color: lightgray;">
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="upload" required/>
            <input type="submit" value="Upload"/>
        </form>
        <?php
        if (isset($_FILES['upload'])) {
            $file = file_get_contents($_FILES['upload']['tmp_name']);
            $caption = "the caption";
            $likes = 34;
            try {
                $stmt = $con->prepare("INSERT INTO post(caption, image, likes, user_id) 
                                       VALUES(:caption, :file, :likes, :userid)
                                      ");
                $stmt->bindParam(":caption", $caption, PDO::PARAM_STR);
                $stmt->bindParam(":file", $file, PDO::PARAM_LOB);
                $stmt->bindParam(":likes", $likes, PDO::PARAM_INT);
                $stmt->bindParam(":userid", $userid, PDO::PARAM_INT);
                $stmt->execute();
                echo "ok";
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }
        
        foreach ($posts as $post) {
            $caption = $post[0];
            $datecreated = $post[1];
            $timecreated = $post[2];
            $likes = $post[3];
            $username = $post[4];
            $image = $post[5];
            $profilepicture = $post[6];

            echo
            '
            <div class="post">
                <div class="postTop">
                    <img class="profilepicture" src="data:image/jpeg; base64, ' . base64_encode($profilepicture) . '">
                    <h3>' . $username . '</h3>
                </div>
                <div class="postMain">
                    <img class="image" src="data:image/jpeg; base64, ' . base64_encode($image) . '">
                </div>
                <div class="postBottom">
                    <p>' . $caption . '</p>
                </div>
            </div>
            ';
        }
        ?>
    </body>
</html>