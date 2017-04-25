<?php

ob_start();
include_once 'admin_header.php';
	
$title = $_POST['title'];
$a_name = $_POST['a_name'];
$a_surname = $_POST['a_surname'];
$p_name = $_POST['p_name'];
$quantity = $_POST['quantity'];

if(addBook($title, $quantity, $a_name, $a_surname, $p_name) == -1) {	/* try to insert book */
	//printf("Insertion failed");
	printInsertMessage(0);
} else {
	//printf("Insertion success");
	printInsertMessage(1);
}

ob_end_flush();
?>