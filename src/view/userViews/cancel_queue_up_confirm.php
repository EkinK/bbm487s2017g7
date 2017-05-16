<html>

<?php include_once 'user_header.php';?>
<link rel="stylesheet" href="css/animate.css">
<link rel="stylesheet" href="css/style.css">

<?php
	$bookID = $_POST['bookID'];
	$title = $_POST['title'];
	$author = $_POST['author'];
	$publisher = $_POST['publisher'];
	$queue_length = $_POST['queue_length'];
	
	echo '<form action="cancel_queue_up.php" method="POST">
		<div class="container">
			<div class="top">
				<br/><br/><br/><br/><br/><h1 id="title"></h1>
				<div class="login-box animated fadeInUp" style="max-width: 300px;">
					<div class="box-header">
						<h2 style="letter-spacing: 0px; font-size: 1.3em;">CONFIRMATION</h2>
					</div>
					<table class="tbl">
					   <tbody>
					   <tr>
						  <td>Title</td>
						  <td>: '.$title.'</td>
					   </tr>
					   <tr>
						  <td>Author</td>
						  <td>: '.$author.'</td>
					   </tr>
					   <tr>
						  <td>publisher</td>
						  <td>: '.$publisher.'</td>
					   </tr>
					   <tr>
						  <td>users left</td>
						  <td>: '.$queue_length.'</td>
					   </tr></tbody>
					</table>
					<p style="color: #888;"> Are you sure? </p>
					<input type="hidden" id="bookID" name="bookID" value="'.$bookID.'">
					<button type="submit">CONFIRM</button>
					<a href="user_book.php" class="cancel_button">CANCEL</a>
				</div>
			</div>
		</div>
	</form>';
?>
</html>