// Main Screen

//function selectScreen(pageid) {
//  var screens = ["home", "search", "mapPage", "profile", "menu"];
//  for (var i = 0; i < screens.length; i++) {
//    if (screens[i] != pageid) {
//      document.getElementById(screens[i]).style.display = "none";
//    } else {
//      document.getElementById(pageid).style.display = "block";
//    }
//  }
//}

// Map Screen

//let map;
//function initMap() {
//  var latlon = {
//    lat: 56.5,
//    lng: -2.92
//  }
//  map = new google.maps.Map(document.getElementById("map"), {
//    center: latlon,
//    zoom: 10,
//  });
//}

//function setMarker(option) {
//    var latlon;
//    switch (option.value) {
//      case "Dudhope":
//        latlon = {
//          lat: 56.46445328031259,
//          lng: -2.986209108314997
//        }
//        break;
//      case "Newport":
//        latlon = {
//          lat: 56.43192139217475,
//          lng: -2.95253923925697676
//        }
//        break;
//      case "Monifieth":
//        latlon = {
//          lat: 56.479082018302165,
//          lng: -2.814025001417361
//        }
//        break;
//    }
//    new google.maps.Marker({
//      position: latlon,
//      map,
//      title: option,
//    });
//}

// Home Screen

//var likes = 10;
//document.getElementById("likes").innerHTML = likes + " Likes";
var liked = false;

function like() {
  if (liked === false) {
    liked = true;
    document.getElementById("like0").style.display = "none";
    document.getElementById("like1").style.display = "inline-block";
//      ++likes;
//    document.getElementById("likes").innerHTML = likes + " Likes";
  } else {
    liked = false;
    document.getElementById("like0").style.display = "inline-block";
    document.getElementById("like1").style.display = "none";
//      --likes;
//    document.getElementById("likes").innerHTML = likes + " Likes";
  }
}

//var likes2 = 10
//document.getElementById("likes2").innerHTML = likes2 + " Likes"
//var liked2 = false
//
//function like2() {
//  if (liked2 == false) {
//    liked2 = true
//    document.getElementById("like02").style.display = "none"
//    document.getElementById("like12").style.display = "inline-block"
//      ++likes2
//    document.getElementById("likes2").innerHTML = likes2 + " Likes"
//  } else {
//    liked2 = false
//    document.getElementById("like02").style.display = "inline-block"
//    document.getElementById("like12").style.display = "none"
//      --likes2
//    document.getElementById("likes2").innerHTML = likes2 + " Likes"
//  }
//}
//
//var likes3 = 10
//document.getElementById("likes3").innerHTML = likes3 + " Likes"
//var liked3 = false
//
//function like3() {
//  if (liked3 == false) {
//    liked3 = true
//    document.getElementById("like03").style.display = "none"
//    document.getElementById("like13").style.display = "inline-block"
//      ++likes3
//    document.getElementById("likes3").innerHTML = likes3 + " Likes"
//  } else {
//    liked3 = false
//    document.getElementById("like03").style.display = "inline-block"
//    document.getElementById("like13").style.display = "none"
//      --likes3
//    document.getElementById("likes3").innerHTML = likes3 + " Likes"
//  }
//}

var commentsopen = false;

function openclosecomments(commentsection) {
  if (commentsopen === false) {
    commentsopen = true;
    $(commentsection).css('display', 'block');
  } else {
    commentsopen = false;
    $(commentsection).css('display', 'none');
    document.getElementById("user1post").style.display = "none";
  }
}

//var commentsopen2 = false
//
//function openclosecomments2(commentsection) {
//  if (commentsopen2 == false) {
//    commentsopen2 = true
//    $(commentsection).css('display', 'block');
//  } else {
//    commentsopen2 = false
//    $(commentsection).css('display', 'none');
//    document.getElementById("user1post2").style.display = "none";
//  }
//}
//
//var commentsopen3 = false
//
//function openclosecomments3(commentsection) {
//  if (commentsopen3 == false) {
//    commentsopen3 = true
//    $(commentsection).css('display', 'block');
//  } else {
//    commentsopen3 = false
//    $(commentsection).css('display', 'none');
//    document.getElementById("user1post3").style.display = "none";
//  }
//}

var noOfComments = 3;

function postComment() {
  document.getElementById('user1commenttext').innerHTML = commentText.value;
  document.getElementById('user1post').style.display = "inline-block";
  noOfComments = 4;
  document.getElementById("commentnumber").innerHTML = noOfComments + " Comments";
}

//var noOfComments2 = 3
//
//function postComment2() {
//  document.getElementById('user1commenttext2').innerHTML = commentText2.value
//  document.getElementById('user1post2').style.display = "inline-block"
//  noOfComments2 = 4
//  document.getElementById("commentnumber2").innerHTML = noOfComments2 + " Comments"
//}
//
//var noOfComments3 = 3
//
//function postComment3() {
//  document.getElementById('user1commenttext3').innerHTML = commentText3.value
//  document.getElementById('user1post3').style.display = "inline-block"
//  noOfComments3 = 4
//  document.getElementById("commentnumber3").innerHTML = noOfComments3 + " Comments"
//}

// Login Screen
//
//function login() {
//  var correctu = "user1";
//  var correctp = "password1";
//
//  if (username.value.toLowerCase() == "user1" && password.value == "password1") {
//    window.location = "SkateRouteHome.html";
//  } else {
//    alert("incorrect");
//  }
//}

// Register Screen

//function register() {
//  var e = email.value;
//  password.value.type = "password";
//  if (!(e.indexOf("@") > 0 && e.substring(e.indexOf("@"), e.length).indexOf(".") > 0))
//    alert('Invalid Email Address');
//  if (password.value != confpassword.value) {
//    alert("Password does not match")
//  }
//}
