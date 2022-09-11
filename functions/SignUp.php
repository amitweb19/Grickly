<?php

if(isset($_POST["emailcheck"])){
	include_once("../app/db_conx.php");
	$email = mysqli_real_escape_string($db_conx, $_POST['emailcheck']);
	$sql = "SELECT id FROM users WHERE email='$email' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $email_check = mysqli_num_rows($query);

    if ($email_check < 1) {
	    echo '<strong style="color:#009900;">' . $email . ' is OK</strong>';
	    exit();
    } else {
	    echo '<strong style="color:#F00;">' . $email . ' is taken</strong>';
	    exit();
    }
}
?><?php

if(isset($_POST["e"])){
	
	include_once("../app/db_conx.php");
	
	$e = mysqli_real_escape_string($db_conx, $_POST['e']);
	$u = explode("@", $e)[0];
	$p = $_POST['p'];
	$fn = $_POST['fn'];
	$n = $_POST['fn'].' '.$_POST['ln'];
	$g = preg_replace('#[^a-z]#', '', $_POST['g']);
	$ul = preg_replace('#[^a-z ]#i', '', $_POST['ul']);
	$d = preg_replace('#[^0-9]#', '', $_POST['y']).'-'.preg_replace('#[^0-9]#', '', $_POST['m']).'-'.preg_replace('#[^0-9]#', '', $_POST['d']);
	
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	
	$sql = "SELECT id FROM users WHERE username='$u' LIMIT 1";
    $query = mysqli_query($db_conx, $sql); 
	$u_check = mysqli_num_rows($query);
	
	$sql = "SELECT id FROM users WHERE email='$e' LIMIT 1";
    $query = mysqli_query($db_conx, $sql); 
	$e_check = mysqli_num_rows($query);
	if (!$e_check) {
    die("Select failed");
}
	
	if($u == "" || $e == "" || $p == "" || $fn == "" || $n == "" || $g == "" || $ul == "" || $d == ""){
		echo "The form submission is missing values.";
        exit();
	} else if ($u_check > 0){ 
        echo "The username you entered is alreay taken";
        exit();
	} else if ($e_check > 0){ 
        echo "That email address is already in use in the system";
        exit();
	} else if (strlen($u) < 3 || strlen($u) > 30) {
        echo "Username must be between 3 and 30 characters";
        exit(); 
    } else if (is_numeric($u[0])) {
        echo 'Username cannot begin with a number';
        exit();
    } else {
	
		$p_hash = md5($p);
		
		$sql = "INSERT INTO users (username, email, password, first_name, full_name, gender, userlevel, dob, ip, signup, lastlogin, notescheck)       
		        VALUES('$u','$e','$p_hash','$fn','$n','$g','$ul','$d','$ip',now(),now(),now())";
		$query = mysqli_query($db_conx, $sql); 
		$uid = mysqli_insert_id($db_conx);
		
		$sql = "INSERT INTO useroptions (id, username, background) VALUES ('$uid','$u','original')";
		$query = mysqli_query($db_conx, $sql);
		
		if (!file_exists("../users/$u")) {
			mkdir("../users/$u", 0755);
			print_r(error_get_last());
		}
		$to = "$e";							 
		$from = "auto_responder@grickly.com";
		$subject = 'Grickly Account Activation';
		$message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Grickly Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#fff; font-size:40px; color:#21B8C6;"><a href="http://www.grickly.com"><img src="http://www.grickly.com/img/logo.png" width="85" height="80" alt="grickly logo" style="border:none; float:left;"></a> &nbsp; Grickly Account Activation</div><div style="padding:24px; font-size:17px;">Hello '.$u.',<br /><br />Click the link below to activate your account when ready:<br /><br /><a href="http://www.grickly.com/functions/activation.php?id='.$uid.'&u='.$u.'&e='.$e.'&p='.$p_hash.'">Click here to activate your account now</a><br /><br />Login after successful activation using your:<br />* E-mail Address: <b>'.$e.'</b></div></body></html>';
		$headers = "From: $from\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
		mail($to, $subject, $message, $headers);
		echo "signup_success";
		exit();
	}
	exit();
}
?>