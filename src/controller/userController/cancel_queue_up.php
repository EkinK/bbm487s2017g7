<?php

	include_once "user_header.php";
	echo '
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/style.css">
	';

	if(isset($_POST['bookID'])) {
		if(cancelQueueUp($_SESSION['userID'], $_POST['bookID']) == 1) {
			/* success message */
			printCancelQueueUpMessage(1);
		}else {
			/* failure message */
			printCancelQueueUpMessage(0);
		}
	}
?>