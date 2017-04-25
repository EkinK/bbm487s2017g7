<?php

	include_once "admin_header.php";
	echo '
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/style.css">
	';
	
	$bookID = $_POST['bookID'];

	/* modify variables */
	$title = $_POST['title'];
	$a_name = $_POST['a_name'];
	$a_surname = $_POST['a_surname'];
	$p_name = $_POST['publisher'];
	$quantity = $_POST['quantity'];
	
	/* old variables */
	$old_title = $_POST['old_title'];
	$old_a_name = $_POST['old_a_name'];
	$old_a_surname = $_POST['old_a_surname'];
	$old_p_name = $_POST['old_publisher'];
	$old_quantity = $_POST['old_quantity'];
	
	if(isset($_POST['bookID'])) {
		if(updateSelectedBook($bookID, 
								$old_title, $title, 
								$old_a_name, $a_name, 
								$old_a_surname, $a_surname, 
								$old_p_name, $p_name, 
								$old_quantity, $quantity)) {
			/* success message */
			printUpdateMessage(1);
		}else {
			/* failure message */
			printUpdateMessage(0);
		}
	}
?>