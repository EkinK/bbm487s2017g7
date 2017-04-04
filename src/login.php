<?php

ob_start();
include_once 'functions.php';
 
$user = $_POST['username'];
$pass = $_POST['password'];
 
$sql = mysqli_query($_SESSION["conn"], "select * from users where username='".$user."' and password='".$pass."' ") or die(mysql_error());

if(mysqli_num_rows($sql))  {
	$authority = mysqli_fetch_object($sql)->authority;
    $_SESSION["login"] = "true";
    $_SESSION["user"] = $user;
    $_SESSION["pass"] = $pass;
	$_SESSION["authority"] = $authority;
	
	if($authority == 4) {
		header("Location:admin.php");
	} else if($authority == 2) {
		header("Location:staff.php");
	} else if($authority == 1) {
		header("Location:user.php");
	}
}
else {
    if($user=="" or $pass=="") {
        echo "<center>Lutfen kullanici adi ya da sifreyi bos birakmayiniz..! <a href=javascript:history.back(-1)>Geri Don</a></center>";
    }
    else {
        echo "<center>Kullanici Adi/Sifre Yanlis.<br><a href=javascript:history.back(-1)>Geri Don</a></center>";
    }
}
 
ob_end_flush();
?>