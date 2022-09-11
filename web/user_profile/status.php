<?php
$status_ui = "";
$statuslist1 = "";
$statuslist2 = "";
$notification_ui = "";
$notification_list = "";
$sql1 = "SELECT * FROM notifications WHERE username LIKE BINARY '$log_username' ORDER BY date_time DESC";
$query1 = mysqli_query($db_conx, $sql1);
$numrows1 = mysqli_num_rows($query1);

$sql2 = "SELECT * FROM friends WHERE user2='$log_username' AND accepted='0' ORDER BY datemade ASC";
$query2 = mysqli_query($db_conx, $sql2);
$numrows2 = mysqli_num_rows($query2);

if(($numrows1 < 1) AND ($numrows2 < 1)){
	$notification_list = '<div id="notificationDoor" onclick="openDoor()">Notification</div>';
	$notification_list .= '<p style="margin: 20px 10px;">You do not have any notifications</p>';
} else {
	$notification_list .= '<div id="notificationDoor" onclick="openDoor()">Notification</div>';
	while ($row1 = mysqli_fetch_array($query1, MYSQLI_ASSOC)) {
		$noteid = $row1["id"];
		$initiator = $row1["initiator"];
		$app = $row1["app"];
		$note = $row1["note"];
		$date_time = $row1["date_time"];
		$date_time = strftime("%b %d, %Y", strtotime($date_time));
		$notification_list .= '<p style="margin: 20px 10px;"><a href='.$initiator.'>'.$initiator.'</a> | '.$app.'<br />'.$note.'</p>';

	}
	while ($row2 = mysqli_fetch_array($query2, MYSQLI_ASSOC)) {
		$reqID = $row2["id"];
		$user1 = $row2["user1"];
		$datemade = $row2["datemade"];
		
		$thumbquery = mysqli_query($db_conx, "SELECT profilepic,full_name FROM users WHERE username='$user1' LIMIT 1");
		$thumbrow = mysqli_fetch_row($thumbquery);
		$user1avatar = $thumbrow[0];
		$user1name = $thumbrow[1];
		$user1pic = '<img src="../../users/'.$user1.'/'.$user1avatar.'" alt="'.$user1.'" onclick="window.location.href=\'profile.php?u='.$user1.'\'" class="user_pic">';
		if($user1avatar == NULL){
			$user1pic = '<img src="images/avatardefault.jpg" alt="'.$user1.'" class="user_pic">';
		}
		
		$time = ($timeAgoObject -> makeAgo($timeAgoObject -> convert_datetime($datemade)));
		$notification_list .= '<div id="friendreq_'.$reqID.'" class="friendrequests">';
		$notification_list .= $user1pic.'<br><a href="profile.php?u='.$user1.'">'.$user1name.'</a>';
		$notification_list .= '<div class="user_info" id="user_info_'.$reqID.'">'.$time.'<br /><br />';
		$notification_list .= '<button onclick="friendReqHandler(\'accept\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">accept</button> or ';
		$notification_list .= '<button onclick="friendReqHandler(\'reject\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">reject</button>';
		$notification_list .= '</div>';
		$notification_list .= '</div>';
	}
}
mysqli_query($db_conx, "UPDATE users SET notescheck=now() WHERE username='$log_username' LIMIT 1");
?><?php
if($isOwner == "yes"){
	$status_ui .= '<div id="textArea">
						<form onsubmit="return false">
						<div id="textAreaOpt1">
							<span class="links" onclick="iBold()"><b>B</b></span>
							<span class="links" onclick="iUnderline()"><u>U</u></span>
							<span class="links" onclick="iItalic()"><i>I</i></span>
							<span class="links" onclick="iFontSize()">Text Size</span>
							<span class="links" onclick="iForeColor()">Text Color</span>
							<span class="links" onclick="iHorizontalRule()">HR</span>
							<span class="links" onclick="iUnorderedList()">OL</span>
							<span class="links" onclick="iOrderedList()">UL</span>
						</div>
						<textarea style="display:none;" id="statustext" placeholder="Write a post here..." cols="100" rows="14"></textarea>
						<iframe name="richTextField" id="richTextfield" ></iframe>
						<div id="textAreaOpt2">
							<div id="linkArea">
								 <span class="linkIcons" onclick="iLink()"><img src="../img/link.png"></span>
								 <span class="linkIcons" onclick="iImage()"><img src="../img/img.png"></span>
								 <span id="imgLoc"></span>
							</div>
							<div id="tagArea">
								<input type="radio" id="public" name="tag" value="pb" checked> <label for="public">Public</label>
								<input type="radio" id="private" name="tag" value="pr"> <label for="private">Private</label>
							</div>
							<div id="buttonArea"><button id="statusBtn" onclick="postToStatus(\'status_post\',\'a\',\''.$u.'\',\'statustext\')">Post</button></div>
						</div>
						</form>
					</div>';

	$notification_ui .= $notification_list;
} else if($isFriend == true && $log_username != $u or $isFollow == true){
	if($userlevel==='t'){
		$known = '<b>Department</b> - <i>'.$department.'</i>';
	} else if($userlevel === 's') {
		$known = '<b>Rating</b> - <i>'.$rating.'/5</i>';
	} else {
		$known = '';
	}
	$status_ui .= '<div id="textArea"><form onsubmit="return false"><div id="textAreaOpt1"></div><textarea id="statustext" onkeyup="statusMax(this,250)" placeholder="Hi '.$logfname.', say something to '.$fname.'"></textarea><div id="textAreaOpt2"><div id="linkArea"></div><div id="tagArea"><input type="radio" id="public" name="tag" value="pb" checked> <label for="public">Public</label> <input type="radio" id="private" name="tag" value="pr"> <label for="private">Private</label></div>';
	$status_ui .= '<div id="buttonArea"><button id="statusBtn" onclick="postToStatus(\'status_post\',\'c\',\''.$u.'\',\'statustext\')">Post</button></div></div></form></div>';
	$notification_ui .= '<div id="notificationDoor" onclick="openDoor()">Details</div><br><p style="margin: 20px;"><b>Gender</b> - <i>'.$sex.'</i><br><br><b>Join Date</b> - <i>'.$joindate.'</i><br><br><b>Last Login</b> - <i>'.$lastsession.'</i><br><br><b>Profession</b> - <i>'.$profession.'</i><br><br>'.$known.'<br><br><b>Date of Birth</b> - <i>'.$dob.'</i></p>';
}
?><?php 
$sql = "SELECT * FROM status WHERE account_name='$u' AND type='a' OR account_name='$u' AND type='c' ORDER BY postdate DESC LIMIT 20";
$query = mysqli_query($db_conx, $sql);
$statusnumrows = mysqli_num_rows($query);
while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
	$statusid = $row["id"];
	$account_name = $row["account_name"];
	$author = $row["author"];
	$postdate = $row["postdate"];
	$data = $row["data"];
	$data = nl2br($data);
	$data = str_replace("&amp;","&",$data);
	$data = stripslashes($data);
	$statusDeleteButton = '';
	if($author == $log_username || $account_name == $log_username ){
		$statusDeleteButton = '<span id="sdb_'.$statusid.'"><a href="#" onclick="return false;" onmousedown="deleteStatus(\''.$statusid.'\',\'status_'.$statusid.'\');" title="DELETE THIS STATUS AND ITS REPLIES">delete status</a></span> &nbsp; &nbsp;';
	}
$pic = mysqli_query($db_conx, "SELECT profilepic, full_name FROM users WHERE username='$author'");
while ($row0 = mysqli_fetch_array($pic, MYSQLI_ASSOC)) {
	$authorpic = $row0["profilepic"];
	$name = $row0["full_name"];
}
if($account_name == $author)
{
	$opt1 = "usr";
	$opt2 = "Left";
}
else
{
	$opt1 = "othr";
	$opt2 = "Right";
}
	// GATHER UP ANY STATUS REPLIES
	$status_replies = "";
	$query_replies = mysqli_query($db_conx, "SELECT * FROM status WHERE osid='$statusid' AND type='b' ORDER BY postdate ASC");
	$replynumrows = mysqli_num_rows($query_replies);
    if($replynumrows > 0){
        while ($row2 = mysqli_fetch_array($query_replies, MYSQLI_ASSOC)) {
			$statusreplyid = $row2["id"];
			$replyauthor = $row2["author"];
			$replydata = $row2["data"];
			$replydata = nl2br($replydata);
			$replypostdate = $row2["postdate"];
			$replydata = str_replace("&amp;","&",$replydata);
			$replydata = stripslashes($replydata);
			$replyDeleteButton = '';
			if($replyauthor == $log_username || $account_name == $log_username ){
				$replyDeleteButton = '<span id="srdb_'.$statusreplyid.'"><a href="#" onclick="return false;" onmousedown="deleteReply(\''.$statusreplyid.'\',\'reply_'.$statusreplyid.'\');" title="DELETE THIS COMMENT">remove</a></span>';
			}
			$status_replies .= '<div id="reply_'.$statusreplyid.'" class="reply_boxes"><div><b>Reply by <a href="user.php?u='.$replyauthor.'">'.$replyauthor.'</a> '.$replypostdate.':</b> '.$replyDeleteButton.'<br />'.$replydata.'</div></div>';
        }
    }
    $statuslist1 .= '<div class="postbox"><div class="'.$opt1.'speak"></div><div class="post'.$opt1.'"><img class="userpic" src="../../users/'.$author.'/'.$authorpic.'" alt="'.$author.'" onclick="window.location.href=\'profile.php?u='.$author.'\'"><a href="profile.php?u='.$author.'">'.$name.'</a><br>'.$postdate.'</div><div class="postdata"><div class="usrdata">'.$data.'</div></div></div><span class="seprater'.$opt2.'"></span>';
	
	if($isFriend == true || $log_username == $u){
	    $statuslist2 .= '<textarea id="replytext_'.$statusid.'" class="replytext" onkeyup="statusMax(this,250)" placeholder="write a comment here"></textarea><button id="replyBtn_'.$statusid.'" onclick="replyToStatus('.$statusid.',\''.$u.'\',\'replytext_'.$statusid.'\',this)">Reply</button>';	
	}
}
?>
<div id="textAreaSection">
<?php echo $status_ui; ?>
	<div id="notification"><?php echo $notification_ui; ?></div>
</div>
<span class="sepraterFull"></span>
<?php echo $statuslist1; ?>