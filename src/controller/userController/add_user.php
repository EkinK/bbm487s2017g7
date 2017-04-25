<?php

ob_start();
include_once 'admin_header.php';
	
$username = $_POST['username'];
$pass = md5($_POST['password']);
$name = $_POST['name'];
$surname = $_POST['surname'];
$authority = $_POST['authority'];

$result = addUser($username, $pass, $name, $surname, $authority);
if($result == -1) {	/* try to insert book */
	
	printInsertUserMessage(0);
} else if($result == -2){

	printInsertUserMessage(2);
} else {
	
	printInsertUserMessage(1);
}

ob_end_flush();
?>