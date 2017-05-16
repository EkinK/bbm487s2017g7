<?php

	include_once "user_header.php";
	echo '
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/style.css">
	';

	if(isset($_POST['bookID'])) {
		if(queueUp($_SESSION['userID'], $_POST['bookID']) == 1) {
			/* success message */
			 printQueueUpMessage(1);
		}else {
			/* failure message */
			 printQueueUpMessage(0);
		}
	}
?>