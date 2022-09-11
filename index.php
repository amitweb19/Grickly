<?php
include_once("app/check_login_status.php");
include "app/config.php";
include "app/detect.php";

if($page_name=='')
	include $browser_t.'/index.php';
elseif($page_name=='index.php')
	include $browser_t.'/index.php';
else
	include $browser_t.'/404.php';
?>
