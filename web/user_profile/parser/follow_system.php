<?php
include_once("../../../app/check_login_status.php");
if($user_ok != true || $log_username == "") {
	exit();
}
?><?php
if (isset($_POST['type']) && isset($_POST['user'])){
	$user = preg_replace('#[^a-z0-9]#i', '', $_POST['user']);
	$sql = "SELECT COUNT(id) FROM users WHERE username='$user' AND activated='1' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$exist_count = mysqli_fetch_row($query);
	if($exist_count[0] < 1){
		mysqli_close($db_conx);
		echo "$user does not exist.";
		exit();
	}
	if($_POST['type'] == "follow"){

		$sql = "SELECT COUNT(id) FROM users WHERE username='$log_username' AND userlevel='t' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$userCheck1 = mysqli_fetch_row($query);
		$sql = "SELECT COUNT(id) FROM users WHERE username='$user' AND userlevel='s' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$userCheck2 = mysqli_fetch_row($query);
	  
        if($userCheck1[0] > 0){
        	echo "You have teacher, So you have not follow any one..";
        } else if($userCheck2[0] > 0){
        	echo "You can't follow any student";
        } else {
	        $sql = "INSERT INTO follows(follower, followed, followdate) VALUES('$log_username','$user',now())";
		    $query = mysqli_query($db_conx, $sql);
			mysqli_close($db_conx);
	        echo "followed_sent";
	        exit();
		}
	} else if($_POST['type'] == "unfollow"){
		$sql = "SELECT COUNT(id) FROM follows WHERE follower='$log_username' AND followed='$user' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$row_count1 = mysqli_fetch_row($query);
		$sql = "SELECT COUNT(id) FROM follows WHERE follower='$user' AND followed='$log_username' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$row_count2 = mysqli_fetch_row($query);
	    if ($row_count1[0] > 0) {
	        $sql = "DELETE FROM follows WHERE follower='$log_username' AND followed='$user' LIMIT 1";
			$query = mysqli_query($db_conx, $sql);
			mysqli_close($db_conx);
	        echo "unfollow_ok";
	        exit();
	    } else if ($row_count2[0] > 0) {
			$sql = "DELETE FROM follows WHERE follower='$user' AND followed='$log_username' LIMIT 1";
			$query = mysqli_query($db_conx, $sql);
			mysqli_close($db_conx);
	        echo "unfollow_ok";
	        exit();
	    } else {
			mysqli_close($db_conx);
	        echo "No following could be found between your account and $user, therefore we cannot unfollow you.";
	        exit();
		}
	}
}
?>