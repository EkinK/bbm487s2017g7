<?php

	include_once "user_header.php";
	echo '
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/style.css">
	';

	if(isset($_POST['borrowID'])) {
		if(returnBook($_POST['borrowID'], $_POST['bookID'], $_POST['quantity']) == 1) {
			/* success message */
			 printReturnBookMessage(1);
		}else {
			/* failure message */
			 printReturnBookMessage(0);
		}
	}
?>