<html>

<?php include_once 'user_header.php';?>
<link rel="stylesheet" href="css/animate.css">
<link rel="stylesheet" href="css/style.css">

<?php
	$borrowID = $_POST['borrowID'];
	$bookID = $_POST['bookID'];
	$title = $_POST['title'];
	$author = $_POST['author'];
	$publisher = $_POST['publisher'];
	$quantity = $_POST['quantity'];
	
	echo '<form action="return_book.php" method="POST">
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
					   </tr></tbody>
					</table>
					<p style="color: #888;"> Do you want to return this book? </p>
					<input type="hidden" id="borrowID" name="borrowID" value="'.$borrowID.'">
					<input type="hidden" id="bookID" name="bookID" value="'.$bookID.'">
					<input type="hidden" id="quantity" name="quantity" value="'.$quantity.'">
					<button type="submit">CONFIRM</button>
					<a href="user_book.php" class="cancel_button">CANCEL</a>
				</div>
			</div>
		</div>
	</form>';
?>
</html>