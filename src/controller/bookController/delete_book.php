<?php

	include_once "admin_header.php";
	echo '
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/style.css">
	';

	if(isset($_POST['bookID'])) {
		if(deleteSelectedBook($_POST['bookID']) == 1) {
			/* success message */
			printDeleteMessage(1);
		}else {
			/* failure message */
			printDeleteMessage(0);
		}
	}
?>