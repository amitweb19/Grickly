<?php
// If user is already logged in, header that weenis away
if($user_ok == true){
	header("location: web/user_profile/profile.php?u=".$_SESSION["username"]);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Grickly</title>
	<link type="text/css" rel="stylesheet" href="web/css/index.css" />
	<script src="web/js/main.js"></script>
	<script src="web/js/ajax.js"></script>
	<script>
		function emptyElement(x){
			_(x).innerHTML = "";
		}
		function login(){
			var el = _("emaill").value;
			var pl = _("passwordl").value;
			if(el == "" || pl == ""){
				_("statusl").innerHTML = '<span style="color: #F00;">Fill out all of the form data</span>';
			} else {
				var ajax = ajaxObj("POST", "functions/LogIn.php");
		        ajax.onreadystatechange = function() {
			        if(ajaxReturn(ajax) == true) {
			            if(ajax.responseText == "login_failed"){
							_("statusl").innerHTML = '<span style="color: #F00;">Login unsuccessful, please try again.</span>';
						} else {
							window.location = "../web/user_profile/profile.php?u="+ajax.responseText;
						}
			        }
		        }
		        ajax.send("e="+el+"&p="+pl);
			}
		}

		function restrict(elem){
			var tf = _(elem);
			var rx = new RegExp;
			if(elem == "email"){
				rx = /[' "]/gi;
			}
			tf.value = tf.value.replace(rx, "");
		}
		function checkemail(){
			var e = _("email").value;
			if(e != ""){
				_("status").innerHTML = 'checking ...';
				var ajax = ajaxObj("POST", "functions/SignUp.php");
		        ajax.onreadystatechange = function() {
			        if(ajaxReturn(ajax) == true) {
			            _("status").innerHTML = ajax.responseText;
			        }
		        }
		        ajax.send("emailcheck="+e);
			}
		}
		function signup(){
			var fn = _("fname").value;
			var ln = _("lname").value;
			var e = _("email").value;
			var ul = _("userlevel").value;
			var p1 = _("pass1").value;
			var p2 = _("pass2").value;
			var d = _("date").value;
			var m = _("month").value;
			var y = _("year").value;
			var g = document.getElementsByName("gender");
			if (document.getElementById('male').checked) {
		  		var g = document.getElementById('male').value;
			} else
				var g = document.getElementById('female').value;
			var status = _("status");
			if(fn == "" || ln == "" || e == "" || ul == "" || p1 == "" || p2 == "" || d == "" || m == "" || y == "" || g == ""){
				status.innerHTML = "Fill out all of the form data";
			} else if(p1 != p2){
				status.innerHTML = "Your password fields do not match";
			} else {
				var ajax = ajaxObj("POST", "functions/SignUp.php");
		        ajax.onreadystatechange = function() {
			        if(ajaxReturn(ajax) == true) {
			            if(ajax.responseText != "signup_success"){
							status.innerHTML = ajax.responseText;
						} else {
							window.open('web/success.php', '_self');
						}
			        }
		        }
		        ajax.send("fn="+fn+"&ln="+ln+"&e="+e+"&ul="+ul+"&p="+p1+"&d="+d+"&m="+m+"&y="+y+"&g="+g);
			}
		}
		
		function toggleOverlay(){
			var overlay = document.getElementById('overlay');
			var specialBox = document.getElementById('specialBox');
			overlay.style.opacity = .8;
			if(overlay.style.display == "block"){
				overlay.style.display = "none";
				specialBox.style.display = "none";
			} else {
				overlay.style.display = "block";
				specialBox.style.display = "block";
			}
		}
	</script>
</head>
<body>
	<div id="overlay"></div>
	<div id="box">
		<?php include 'web/include/indexBodyheader.php'; ?>
		<?php include 'web/include/indexBodycontainer.php'; ?>
		<?php include 'web/include/indexBodyfooter.php'; ?>
	</div>
	<div id="specialBox">
		<p>Special box content ...</p> 
		<button onmousedown="toggleOverlay()">Close Overlay</button>
	</div>
</body>
</html>