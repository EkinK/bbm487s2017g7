<?php

	include_once "admin_header.php";
	echo '
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/style.css">
	';

	$userID = $_POST['userID'];
	
	/* modify variables */
	$username = $_POST['username'];
	$password = NULL;	/* hash of an empty string is not null ! */
	if($_POST['password'] != NULL) {
		$password = md5($_POST['password']);
	}
	$name = $_POST['name'];
	$surname = $_POST['surname'];
	
	/* old variables */
	$old_username = $_POST['old_username'];
	$old_password = $_POST['old_password'];
	$old_name = $_POST['old_name'];
	$old_surname = $_POST['old_surname'];
	
	if(isset($_POST['userID'])) {
		if(updateSelectedUser($userID, 
								$old_username, $username, 
								$old_password, $password, 
								$old_name, $name, 
								$old_surname, $surname)) {
			/* success message */
			printUpdateUserMessage(1);
		}else {
			/* failure message */
			printUpdateUserMessage(0);
		}
	}
?>