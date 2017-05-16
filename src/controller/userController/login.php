<?php

ob_start();
include_once 'functions.php';
 
$user = $_POST['username'];
$pass = md5($_POST['password']);
 
$sql = mysqli_query($_SESSION["conn"], "select * from users where username='".$user."' and password='".$pass."' ") or die(mysql_error());

if(mysqli_num_rows($sql))  {
	$record = mysqli_fetch_object($sql);
	$authority = $record->authority;
    $_SESSION["login"] = "true";
    $_SESSION["user"] = $user;
    $_SESSION["pass"] = $pass;
	$_SESSION["name"] = $record->name;
	$_SESSION["userID"] = $record->userID;
	
	if($authority == 4) {
		header("Location:admin.php");
	} else if($authority == 2) {
		header("Location:staff.php");
	} else if($authority == 1) {
		header("Location:user.php");
	}
}
else { /* redirect to index page */
	$_SESSION["login"] = "false";
	 header("Location:index.php");
}
 
ob_end_flush();
?>