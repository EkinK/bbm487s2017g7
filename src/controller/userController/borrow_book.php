<?php

	include_once "user_header.php";
	echo '
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/style.css">
	';

	if(isset($_POST['bookID'])) {
		if(borrowBook($_SESSION['userID'], $_POST['bookID']) == 1) {
			/* success message */
			 printBorrowBookMessage(1);
		}else {
			/* failure message */
			 printBorrowBookMessage(0);
		}
	}
?>