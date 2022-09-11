<?php
include_once("../../app/check_login_status.php");
include_once("../../functions/time_ago.php");
$timeAgoObject = new convertToAgo;
// Initialize any variables that the page might echo
$u = "";
$fname = "";
$name = "";
$logname = "";
$logfname = "";
$sex = "Male";
$profession = "";
$profile_pic = "";
$profile_pic_btn = "";
$rating = "";
$theme = "";
$pic_form = "";
$userlevel = "";
$joindate = "";
$lastsession = "";

$thumbquery = mysqli_query($db_conx, "SELECT first_name, full_name FROM users WHERE username='$log_username' LIMIT 1");
$thumbrow = mysqli_fetch_row($thumbquery);
$logfname = $thumbrow[0];
$logname = $thumbrow[1];
// Make sure the _GET username is set, and sanitize it
if(isset($_GET['u'])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
} else {
    header("location: www.grickly.com");
    exit();	
}
// Select the member from the users table
$sql = "SELECT * FROM users WHERE username='$u' AND activated='1' LIMIT 1";
$user_query = mysqli_query($db_conx, $sql);
// Now make sure that user exists in the table
$numrows = mysqli_num_rows($user_query);
if($numrows < 1){
	echo "That user does not exist or is not yet activated, press back";
    exit();	
}
// Check to see if the viewer is the account owner
$isOwner = "no";
if($u == $log_username && $user_ok == true){
	$isOwner = "yes";
	$profile_pic_btn = '<a href="#" onclick="return false;" onmousedown="toggleElement(\'pic_form\')">Change Picture</a>';
	$pic_form  = '<form id="pic_form" enctype="multipart/form-data" method="post" action="parser/photo_system.php">';
	$pic_form .=   '<br><br><input type="file" name="profilepic" style="cursor: pointer;" required>';
	$pic_form .=   '<p><br><input type="submit" value="Upload" style="cursor: pointer;"></p>';
	$pic_form .= '</form>';
}
// Check to see if the viewer is the account owner
$isOwner = "no";
if($u == $log_username && $user_ok == true){
	$isOwner = "yes";

}
// Fetch the user row from the query above
while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
	$profile_id = $row["id"];
	$fname = $row["first_name"];
	$name = $row["full_name"];
	$gender = $row["gender"];
	$userlevel = $row["userlevel"];
	$profilepic = $row["profilepic"];
	$date = $row["dob"];
	$rating = $row["rating"];
	$theme = $row["theme"];
	$department = $row["department"];
	$signup = $row["signup"];
	$lastlogin = $row["lastlogin"];
	$joindate = strftime("%b %d, %Y", strtotime($signup));
	$lastsession .= ($timeAgoObject -> makeAgo($timeAgoObject -> convert_datetime($lastlogin)));
}

$dob = date("d-m-Y", strtotime($date));

if($gender === "f"){
	$sex = "Female";
}
if($userlevel === "s"){
	$profession = "Student";
} else if($userlevel === "t") {
	$profession = "Teacher";
} else {
	$profession = "Other";
}
$profile_pic = '<img src="../../users/'.$u.'/'.$profilepic.'" alt="'.$u.'">';
if($profilepic == NULL){
	if($gender == 'm'){
		$profile_pic = '<img src="img/profile.jpg" alt="'.$u.'">';
	} else {
		$profile_pic = '<img src="img/profile2.jpg" alt="'.$u.'">';
	}
}
?>
<?php
$isFriend = false;
$frnd_req = false;
$isFollow = false;
$ownerBlockViewer = false;
$viewerBlockOwner = false;
if($u != $log_username && $user_ok == true){
	$friend_check = "SELECT id FROM friends WHERE user1='$log_username' AND user2='$u' AND accepted='1' OR user1='$u' AND user2='$log_username' AND accepted='1' LIMIT 1";
	if(mysqli_num_rows(mysqli_query($db_conx, $friend_check)) > 0){
        $isFriend = true;
    }
    $friendReq_check = "SELECT COUNT(id) FROM friends WHERE user1='$log_username' AND user2='$u' AND accepted='0' LIMIT 1";
    if(mysqli_num_rows(mysqli_query($db_conx, $friendReq_check)) > 0){

		$frnd_req = true;
	}
	$follow_check = "SELECT id FROM follows WHERE follower='$log_username' AND followed='$u' LIMIT 1";
	if(mysqli_num_rows(mysqli_query($db_conx, $follow_check)) > 0){
        $isFollow = true;
    }
	$block_check1 = "SELECT id FROM blockedusers WHERE blocker='$u' AND blockee='$log_username' LIMIT 1";
	if(mysqli_num_rows(mysqli_query($db_conx, $block_check1)) > 0){
        $ownerBlockViewer = true;
    }
	$block_check2 = "SELECT id FROM blockedusers WHERE blocker='$log_username' AND blockee='$u' LIMIT 1";
	if(mysqli_num_rows(mysqli_query($db_conx, $block_check2)) > 0){
        $viewerBlockOwner = true;
    }
}
?>
<?php
$frndCheck = false;
$followCheck = false;
if($rating == 3)
{
	$upStars = "★★★";
	$downStars = "★★";
} elseif ($rating == 4) {
	$upStars = "★★★★";
	$downStars = "★";
} elseif ($rating == 5) {
	$upStars = "★★★★★";
	$downStars = "";
} elseif ($rating == 2) {
	$upStars = "★★";
	$downStars = "★★★";
} elseif ($rating == 1) {
	$upStars = "★";
	$downStars = "★★★★";
}
$button = '<span style="color: gold; text-shadow:1px 1px #bbb, 2px 2px #666, .1em .1em .2em rgba(0,0,0,.5);">'.$upStars.'</span><span>'.$downStars.'</span>';
// LOGIC FOR FRIEND AND FOLLOW BUTTON

$ulvCheck1 = "SELECT id FROM users WHERE username='$log_username' AND userlevel='$userlevel' LIMIT 1";
$ulvCheck2 = "SELECT id FROM users WHERE username='$log_username' AND userlevel='s' AND userlevel!='$userlevel' LIMIT 1";
if(mysqli_num_rows(mysqli_query($db_conx, $ulvCheck1)) > 0){
    $frndCheck = true;
} else if(mysqli_num_rows(mysqli_query($db_conx, $ulvCheck2)) > 0){
	$followCheck = true;
}

if($frndCheck == true){
	if($isFriend == true){
		$button = '<button onclick="friendToggle(\'unfriend\',\''.$u.'\',\'Btn\')">Unfriend</button>';
	} else if($user_ok == true && $u != $log_username && $ownerBlockViewer == false){
		$button = '<button onclick="friendToggle(\'friend\',\''.$u.'\',\'Btn\')">Add Friend</button>';
	} else if($frnd_req == true){
		$button = '<button onclick="friendToggle(\'cancel\',\''.$u.'\',\'Btn\')">Friend Request Sent</button>';
	}
} else if ($followCheck == true) {
	if($isFollow == true){
		$button = '<button onclick="followToggle(\'unfollow\',\''.$u.'\',\'Btn\')">Following</button>';
	} else {
		$button = '<button onclick="followToggle(\'follow\',\''.$u.'\',\'Btn\')">Follow</button>';
	}
}
?>
<?php
$friendsHTML = '';
$friends_view_all_link = '';
$frndBox = '';
$sql = "SELECT COUNT(id) FROM friends WHERE user1='$u' AND accepted='1' OR user2='$u' AND accepted='1'";
$query = mysqli_query($db_conx, $sql);
$query_count = mysqli_fetch_row($query);
$friend_count = $query_count[0];
$frndBox = $friend_count." friends";
if($friend_count < 1){
	$friendsHTML = "<br>&nbsp;&nbsp;&nbsp;&nbsp;".$fname." has no friends yet...";
} else {
	$max = 25;
	$all_friends = array();
	$sql = "SELECT user1 FROM friends WHERE user2='$u' AND accepted='1' ORDER BY RAND() LIMIT $max";
	$query = mysqli_query($db_conx, $sql);
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		array_push($all_friends, $row["user1"]);
	}
	$sql = "SELECT user2 FROM friends WHERE user1='$u' AND accepted='1' ORDER BY RAND() LIMIT $max";
	$query = mysqli_query($db_conx, $sql);
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		array_push($all_friends, $row["user2"]);
	}
	$friendArrayCount = count($all_friends);
	if($friendArrayCount > $max){
		array_splice($all_friends, $max);
	}
	if($friend_count > 0){
		$friends_view_all_link = '<a href="view_friends.php?u='.$u.'">View All</a>';
	}
	$orLogic = '';
	foreach($all_friends as $key => $user){
			$orLogic .= "username='$user' OR ";
	}
	$orLogic = chop($orLogic, "OR ");
	$sql = "SELECT username, profilepic FROM users WHERE $orLogic AND profilepic != 'NULL'";
	$query = mysqli_query($db_conx, $sql);
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$friend_username = $row["username"];
		$friend_avatar = $row["profilepic"];
		if($friend_avatar != ""){
			$friend_pic = '../../users/'.$friend_username.'/'.$friend_avatar.'';
		} else {
			$friend_pic = 'img/profile.jpg';
		}
		$friendsHTML .= '<a href="../user_profile/profile.php?u='.$friend_username.'"><img class="friendpics" src="'.$friend_pic.'" alt="'.$friend_username.'" title="'.$friend_username.'"></a>';
	}
}
?>

<?php
$followHTML = '';
$boxTitle = '';
$follows_view_all_link = '';

if($userlevel == 's')
{
	$sql = "SELECT COUNT(id) FROM follows WHERE follower='$u'";
	$query = mysqli_query($db_conx, $sql);
	$query_count = mysqli_fetch_row($query);
	$follow_count = $query_count[0];
	$boxTitle = $follow_count." following";
	if($follow_count < 1){
		$followHTML = "<br>&nbsp;&nbsp;&nbsp;&nbsp;".$fname." is not following anyone...";
	} else {
		$max = 25;
		$all_following = array();
		$sql = "SELECT followed FROM follows WHERE follower='$u' ORDER BY RAND() LIMIT $max";
		$query = mysqli_query($db_conx, $sql);
		while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
			array_push($all_following, $row["followed"]);
		}
		$followedArrayCount = count($all_following);
		if($followedArrayCount > $max){
			array_splice($all_following, $max);
		}
		if($follow_count > 0){
			$follows_view_all_link = '<a href="view_follows.php?u='.$u.'">View All</a>';
		}
		$orLogic = '';
		foreach($all_following as $key => $user){
				$orLogic .= "username='$user' OR ";
		}
		$orLogic = chop($orLogic, "OR ");
		$sql = "SELECT username, profilepic FROM users WHERE $orLogic AND profilepic != 'NULL'";
		$query = mysqli_query($db_conx, $sql);
		while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
			$followed_username = $row["username"];
			$followed_avatar = $row["profilepic"];
			if($followed_avatar != ""){
				$followed_pic = '../../users/'.$followed_username.'/'.$followed_avatar.'';
			} else {
				$followed_pic = 'images/avatardefault.jpg';
			}
			$followHTML .= '<a href="../user_profile/profile.php?u='.$followed_username.'"><img class="friendpics" src="'.$followed_pic.'" alt="'.$followed_username.'" title="'.$followed_username.'"></a>';
		}
	}
} elseif ($userlevel == 't') {
	$sql = "SELECT COUNT(id) FROM follows WHERE followed='$u'";
	$query = mysqli_query($db_conx, $sql);
	$query_count = mysqli_fetch_row($query);
	$follow_count = $query_count[0];
	$boxTitle = $follow_count." followers";
	if($follow_count < 1){
		$followHTML = "<br>&nbsp;&nbsp;&nbsp;&nbsp;".$fname.", You haven't any follower yet...";
	} else {
		$max = 25;
		$all_follower = array();
		$sql = "SELECT follower FROM follows WHERE followed='$u' ORDER BY RAND() LIMIT $max";
		$query = mysqli_query($db_conx, $sql);
		while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
			array_push($all_follower, $row["follower"]);
		}
		$followerArrayCount = count($all_follower);
		if($followerArrayCount > $max){
			array_splice($all_follower, $max);
		}
		if($follow_count > 0){
			$follows_view_all_link = '<a href="view_follows.php?u='.$u.'">View All</a>';
		}
		$orLogic = '';
		foreach($all_follower as $key => $user){
				$orLogic .= "username='$user' OR ";
		}
		$orLogic = chop($orLogic, "OR ");
		$sql = "SELECT username, profilepic FROM users WHERE $orLogic AND profilepic != 'NULL'";
		$query = mysqli_query($db_conx, $sql);
		while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
			$followed_username = $row["username"];
			$followed_avatar = $row["profilepic"];
			if($followed_avatar != ""){
				$followed_pic = '../../users/'.$followed_username.'/'.$followed_avatar.'';
			} else {
				$followed_pic = 'images/avatardefault.jpg';
			}
			$followHTML .= '<a href="../user_profile/profile.php?u='.$followed_username.'"><img class="friendpics" src="'.$followed_pic.'" alt="'.$followed_username.'" title="'.$followed_username.'"></a>';
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $name; ?></title>
<link rel="stylesheet" type="text/css" href="../css/style1.css">
<link rel="stylesheet" type="text/css" href="<?php echo '../css/'.$theme.'.css'; ?>">
<script src="../js/main.js"></script>
<script src="../js/ajax.js"></script>
<script src="../js/wysiwyg.js"></script>
<script type="text/javascript">
function friendToggle(type,user,elem){
	var conf = confirm("Press OK to confirm the '"+type+"' action for <?php echo $name; ?>.");
	if(conf != true){
		return false;
	}
	_(elem).innerHTML = 'please wait ...';
	var ajax = ajaxObj("POST", "parser/friends_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "friend_request_sent"){
				_(elem).innerHTML = '<button onclick="friendToggle(\'cancel\',\'<?php echo $u; ?>\',\'Btn\')">Friend Request Sent</button>';
			} else if(ajax.responseText == "unfriend_ok" || ajax.responseText == "cancel"){
				_(elem).innerHTML = '<button onclick="friendToggle(\'friend\',\'<?php echo $u; ?>\',\'Btn\')">Add Friend</button>';
			} else {
				alert(ajax.responseText);
				_(elem).innerHTML = 'Try again later';
			}
		}
	}
	ajax.send("type="+type+"&user="+user);
}
function friendReqHandler(action,reqid,user1,elem){
	var conf = confirm("Press OK to '"+action+"' this friend request.");
	if(conf != true){
		return false;
	}
	_(elem).innerHTML = "processing ...";
	var ajax = ajaxObj("POST", "parser/friends_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "accept_ok"){
				_(elem).innerHTML = "<b>Request Accepted!</b><br />Your are now friends";
			} else if(ajax.responseText == "reject_ok"){
				_(elem).innerHTML = "<b>Request Rejected</b><br />You chose to reject friendship with this user";
			} else {
				_(elem).innerHTML = ajax.responseText;
			}
		}
	}
	ajax.send("action="+action+"&reqid="+reqid+"&user1="+user1);
}
function followToggle(type,user,elem){
	var conf = confirm("Press OK to confirm the '"+type+"' action for <?php echo $name; ?>.");
	if(conf != true){
		return false;
	}
	_(elem).innerHTML = 'please wait ...';
	var ajax = ajaxObj("POST", "parser/follow_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "followed_sent"){
				_(elem).innerHTML = '<button onclick="followToggle(\'unfollow\',\'<?php echo $u; ?>\',\'Btn\')">Following</button>';
			} else if(ajax.responseText == "unfollow_ok"){
				_(elem).innerHTML = '<button onclick="followToggle(\'follow\',\'<?php echo $u; ?>\',\'Btn\')">Follow</button>';
			} else {
				alert(ajax.responseText);
				_(elem).innerHTML = 'Try again later';
			}
		}
	}
	ajax.send("type="+type+"&user="+user);
}
function toggleNavPanel(x,size){
    var panel = document.getElementById(x), navarrow = document.getElementById("navarrow"), maxH=size;
    if(panel.style.height == maxH){
        panel.style.height = "0px";
        navarrow.innerHTML = "&#9662;";
    } else {
        panel.style.height = maxH;
        navarrow.innerHTML = "&#9652;";
    }
}
function postToStatus(action,type,user,ta){
	var data = _(ta).value;
	if(type === "a")
		data = window.frames['richTextField'].document.body.innerHTML;
	if(data == ""){
		alert("Type something first weenis");
		return false;
	}
	_("statusBtn").disabled = true;
	var ajax = ajaxObj("POST", "parser/status_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			var datArray = ajax.responseText.split("|");
			if(datArray[0] == "post_ok"){
				var sid = datArray[1];
				data = data.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n/g,"<br />").replace(/\r/g,"<br />");
				var currentHTML = _("statusarea").innerHTML;
				_("statusarea").innerHTML = '<div id="status_'+sid+'" class="status_boxes"><div><b>Posted by you just now:</b> <span id="sdb_'+sid+'"><a href="#" onclick="return false;" onmousedown="deleteStatus(\''+sid+'\',\'status_'+sid+'\');" title="DELETE THIS STATUS AND ITS REPLIES">delete status</a></span><br />'+data+'</div></div><textarea id="replytext_'+sid+'" class="replytext" onkeyup="statusMax(this,250)" placeholder="write a comment here"></textarea><button id="replyBtn_'+sid+'" onclick="replyToStatus('+sid+',\'<?php echo $u; ?>\',\'replytext_'+sid+'\',this)">Reply</button>'+currentHTML;
				_("statusBtn").disabled = false;
				_(ta).value = "";
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action="+action+"&type="+type+"&user="+user+"&data="+data);		
}
function openDoor(){
	door = document.getElementById('notificationDoor');
		if (door.style.display === "") {
                 visibility = "hidden";
 				 door.style.opacity = "0";
  				 door.style.transition = "visibility 0s 1s, opacity 1s linear";
        }
}

var timeOut;
function scrollToTop() {
	if (document.body.scrollTop!=0 || document.documentElement.scrollTop!=0){
		window.scrollBy(0,-50);
		timeOut=setTimeout('scrollToTop()',10);
	} else clearTimeout(timeOut);
}
</script>
</head>
<body onload="iFrameOn();">
<div id="header1">
	<div id="wrapheader1">
		<a href="http://www.grickly.com"><div id="logo" class="icons icon7"></div></a>
		<div id="search"><input type="text"><input type="submit" value="Search"></div>
		<div id="link">
			<a href="profile.php?u=<?php echo $log_username; ?>"><?php echo $logname; ?></a>
			<span id="theme" onclick="toggleNavPanel('scrollDown1','205px')">Theme
				<span id="scrollDown1">
			      	<span class="scrollDropSection">Default</span>
			      	<span class="scrollDropSection">Blue</span>
			      	<span class="scrollDropSection">Green</span>
			      	<span class="scrollDropSection">Orange</span>
			      	<span class="scrollDropSection">Black</span>
				</span>
			</span>
			<span id="setting" onclick="toggleNavPanel('scrollDown2','164px')">Setting
				<span id="scrollDown2">
			      	<span class="scrollDropSection">Public Information</span>
			      	<span class="scrollDropSection">Account Password</span>
			      	<span class="scrollDropSection">Account Email</span>
			      	<span class="scrollDropSection">Deactivate Account</span>
				</span>
			</span>
			<a href="../../functions/LogOut.php">Logout</a>
		</div>
	</div>
</div>