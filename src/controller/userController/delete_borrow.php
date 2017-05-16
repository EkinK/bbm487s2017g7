<?php

	include_once "admin_header.php";
	echo '
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/style.css">
	';

	if(isset($_POST['bookID'])) {
		if(deleteBorrow($_POST['userID'], $_POST['bookID'], $_POST['quantity']) == 1) {
			/* success message */
			printDeleteBorrowMessage(1);
		}else {
			/* failure message */
			printDeleteBorrowMessage(0);
		}
	}
?>