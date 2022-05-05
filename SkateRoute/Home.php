<?php
require 'Includes/connection.php';
session_start();

$userid = $_SESSION["id"];

//$posts = array();

$getfollowing = $con->prepare("
        SELECT following
        FROM follow
        WHERE follower = :userid
    ");
$getfollowing->bindParam(":userid", $userid, PDO::PARAM_INT);
$getfollowing->execute();
$following = $getfollowing->fetchAll(PDO::FETCH_COLUMN);

$qMarks = '';

if (empty($following)) {
    $following = array(-1);
}

if (count($following) > 0) {
    $qMarks = str_repeat('?,', count($following) - 1) . '?';
}

$getpost = $con->prepare("
        SELECT post.caption, post.datecreated, post.timecreated, post.likes, user.username, post.image, user.profilepicture 
        FROM post 
        INNER JOIN user on post.user_id = user.id 
        WHERE post.user_id IN ($qMarks) OR post.user_id = $userid
    ");

$getpost->execute($following);

$posts = $getpost->fetchAll();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <title>Skate Route</title>
        <link rel="stylesheet" href="css/Global.css">
        <link rel="stylesheet" href="css/Home.css">
    </head>

    <body>
        <div class="container">
            <div class="topBar">
                <div id="title">
                    <h1>SkateRoute</h1>
                </div>
                <a href="index.php">Log Out</a>
                <div class="message">
                    <img src="Assets/Images/messageIcon.jpg" alt="">
                </div>
            </div>
            <div class="main">
                <div id="home">
                    <div class="post">
                        <form method="post" enctype="multipart/form-data">
                            <input type="file" name="upload" required/>
                            <input type="text" name="caption" required/>
                            <input type="submit" value="Upload"/>
                        </form>
                    </div>
                    <?php
                    if (isset($_FILES['upload'])) {
                        $file = file_get_contents($_FILES['upload']['tmp_name']);
                        $caption = $_POST["caption"];
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
                            header("Location: Home.php");
                        } catch (Exception $ex) {
                            echo $ex->getMessage();
                        }
                    }
                    if (count($posts) > 0) {
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
                                    <img class="like0" src="Assets/Images/Likezero.svg" alt="" onclick="like()">
                                    <img class="like1" src="Assets/Images/Likeone.svg" alt="" onclick="like()">
                                </div>
                            </div>
                            ';
                        }
                    }
                    ?>
                    <!--</div>-->
                    <!--        <div class="post">
                              <div class="postTop">
                                <img src="Assets/Images/ProfilePictureIcon.svg">
                                <h3>Jim Smith</h3>
                              </div>
                              <div class="postMain">
                                <img src="Assets/Images/box.jpg" alt="">
                              </div>
                              <div class="postBottom">
                                <p id="commentnumber2" onclick="openclosecomments2('.postcomments2')">3 Comments</p>
                                <p id="likes2"></p>
                                <img id="like02" src="Assets/Images/Likezero.svg" alt="" onclick="like2()">
                                <img id="like12" src="Assets/Images/Likeone.svg" alt="" onclick="like2()">
                              </div>
                              <div id="post1comments">
                                <div class="postcomments2">
                                  <div class="nameAndPFP">
                                    <img src="Assets/Images/ProfilePictureIcon.svg" width="20">
                                    <h5>Camille Macgregor</h5>
                                  </div>
                                  <p>Good picture.</p>
                                </div>
                                <div class="postcomments2">
                                  <div class="nameAndPFP">
                                    <img src="Assets/Images/ProfilePictureIcon.svg" width="20">
                                    <h5>Jeremy Hurley</h5>
                                  </div>
                                  <p>yes</p>
                                </div>
                                <div class="postcomments2">
                                  <div class="nameAndPFP">
                                    <img src="Assets/Images/ProfilePictureIcon.svg" width="20">
                                    <h5>Jonas Dunlop</h5>
                                  </div>
                                  <p>horrible picture</p>
                                </div>
                                <div id="user1post2">
                                  <div class="nameAndPFP">
                                    <img src="Assets/Images/ProfilePictureIcon.svg" width="20">
                                    <h5>user1</h5>
                                  </div>
                                  <p id="user1commenttext2"></p>
                                </div>
                                <div class="postcomments2">
                                  <form class="" action="index.html" method="post">
                                    <input id="commentText2" type="text" name="commentText2" value="" placeholder="Write a comment.">
                                    <button type="button" name="button2" onclick="postComment2()">Post Comment</button>
                                  </form>
                                </div>
                              </div>
                            </div>
                            <div class="post">
                              <div class="postTop">
                                <img src="Assets/Images/ProfilePictureIcon.svg">
                                <h3>Sonny Clark</h3>
                              </div>
                              <div class="postMain">
                                <img src="Assets/Images/box.jpg" alt="">
                              </div>
                              <div class="postBottom">
                                <p id="commentnumber3" onclick="openclosecomments3('.postcomments3')">3 Comments</p>
                                <p id="likes3"></p>
                                <img id="like03" src="Assets/Images/Likezero.svg" alt="" onclick="like3()">
                                <img id="like13" src="Assets/Images/Likeone.svg" alt="" onclick="like3()">
                              </div>
                              <div id="post1comments">
                                <div class="postcomments3">
                                  <div class="nameAndPFP">
                                    <img src="Assets/Images/ProfilePictureIcon.svg" width="20">
                                    <h5>Elen Dougherty</h5>
                                  </div>
                                  <p>nice</p>
                                </div>
                                <div class="postcomments3">
                                  <div class="nameAndPFP">
                                    <img src="Assets/Images/ProfilePictureIcon.svg" width="20">
                                    <h5>Rita Marsden</h5>
                                  </div>
                                  <p>great</p>
                                </div>
                                <div class="postcomments3">
                                  <div class="nameAndPFP">
                                    <img src="Assets/Images/ProfilePictureIcon.svg" width="20">
                                    <h5>Shaunna Watkins</h5>
                                  </div>
                                  <p>ok</p>
                                </div>
                                <div id="user1post3">
                                  <div class="nameAndPFP">
                                    <img src="Assets/Images/ProfilePictureIcon.svg" width="20">
                                    <h5>user1</h5>
                                  </div>
                                  <p id="user1commenttext3"></p>
                                </div>
                                <div class="postcomments3">
                                  <form class="" action="index.html" method="post">
                                    <input id="commentText3" type="text" name="commentText3" value="" placeholder="Write a comment.">
                                    <button type="button" name="button3" onclick="postComment3()">Post Comment</button>
                                  </form>
                                </div>
                              </div>
                            </div>
                    
                          </div>
                          <div id="search">
                            <h1 style="text-align: center; padding: 1em;">Not done yet.</h1>
                            <input type="search" placeholder="Search">
                            <img src="Assets/Images/box.jpg" alt="">
                            <img src="Assets/Images/box.jpg" alt="">
                            <img src="Assets/Images/box.jpg" alt="">
                            <img src="Assets/Images/box.jpg" alt="">
                            <img src="Assets/Images/box.jpg" alt="">
                            <img src="Assets/Images/box.jpg" alt="">
                            <img src="Assets/Images/box.jpg" alt="">
                            <img src="Assets/Images/box.jpg" alt="">
                            <img src="Assets/Images/box.jpg" alt="">
                            <img src="Assets/Images/box.jpg" alt="">
                            <img src="Assets/Images/box.jpg" alt="">
                            <img src="Assets/Images/box.jpg" alt="">
                            <img src="Assets/Images/box.jpg" alt="">
                            <img src="Assets/Images/box.jpg" alt="">
                            <img src="Assets/Images/box.jpg" alt="">
                            <img src="Assets/Images/box.jpg" alt="">
                            <img src="Assets/Images/box.jpg" alt="">
                            <img src="Assets/Images/box.jpg" alt="">
                            <img src="Assets/Images/box.jpg" alt="">
                            <img src="Assets/Images/box.jpg" alt="">
                          </div>
                          <div id="mapPage">
                            <div id="map"></div>
                            <div id="pingSelectors">
                              <div class="pingSelector">
                                <form class="" action="index.html" method="post">
                                  <select id="skatepark1" name="skatepark">
                                    <option value="Dudhope">Dudhope Skatepark</option>
                                    <option value="Newport">Newport Skatepark</option>
                                    <option value="Monifieth">Monifieth Skatepark</option>
                                  </select>
                                  <button onclick="setMarker(skatepark1)" type="button" name="button">Set Marker</button>
                                </form>
                              </div>
                              <div class="pingSelector">
                                <form class="" action="index.html" method="post">
                                  <select id="skatepark2" name="skatepark">
                                    <option value="Dudhope">Dudhope Skatepark</option>
                                    <option value="Newport">Newport Skatepark</option>
                                    <option value="Monifieth">Monifieth Skatepark</option>
                                  </select>
                                  <button onclick="setMarker(skatepark2)" type="button" name="button">Set Marker</button>
                              </div>
                            </div>
                          </div>
                          <div id="profile">
                            <h1 style="text-align: center; padding: 1em;">Not done yet.</h1>
                          </div>
                        </div>
                    -->                        <div class="bottomBar">
                        <div class="icon" onclick="selectScreen('home')">
                            <img src="Assets/Images/HomeIcon.svg" alt="">
                        </div>
                        <div class="icon" onclick="selectScreen('search')">
                            <img src="Assets/Images/SearchIcon.svg" alt="">
                        </div>
                        <div class="icon" onclick="selectScreen('mapPage')">
                            <img src="Assets/Images/MapIcon.svg" alt="">
                        </div>
                        <div class="icon" onclick="selectScreen('profile')">
                            <img src="Assets/Images/ProfileIcon.svg" alt="">
                        </div>
                        <div class="icon" onclick="selectScreen('menu')">
                            <img id="last" src="Assets/Images/MenuIcon.svg" alt="">
                        </div>
                    </div>
                </div>
<!--                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC6OQm2j00EGxTcawMk97B4efG6_u62aPE&callback=initMap" type="text/javascript"></script>-->
                </body>
                <script type="text/javascript" src="js/SkateRoute.js"></script>

                </html>
