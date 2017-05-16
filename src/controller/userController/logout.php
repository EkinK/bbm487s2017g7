<?php
	include_once 'functions.php';
	mysqli_close($_SESSION["conn"]); /* close database connection */
	session_destroy();	/* destroy session */
	
	header("Location:index.php");
?>