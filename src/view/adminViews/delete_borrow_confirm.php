<html>

<?php include_once 'admin_header.php';?>
<link rel="stylesheet" href="css/animate.css">
<link rel="stylesheet" href="css/style.css">

<?php
	$bookID = $_POST['bookID'];
	$userID = $_POST['userID'];
	$username = $_POST['username'];
	$title = $_POST['title'];
	$author = $_POST['author'];
	$publisher = $_POST['publisher'];
	$quantity = $_POST['quantity'];
	$borrow_date = $_POST['borrow_date'];
	$delivery_date = $_POST['delivery_date'];
	
	
	echo '<form action="delete_borrow.php" method="POST">
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
						  <td>Username</td>
						  <td>: '.$username.'</td>
					   </tr>
					   <tr>
						  <td>Title</td>
						  <td>: '.$title.'</td>
					   </tr>
					   <tr>
						  <td>Author</td>
						  <td>: '.$author.'</td>
					   </tr>
					   <tr>
						  <td>Publisher</td>
						  <td>: '.$publisher.'</td>
					   </tr>
					   <tr>
						  <td>Borrow Date</td>
						  <td>: '.$borrow_date.'</td>
					   </tr>
					   <tr>
						  <td>Expire Date</td>
						  <td>: '.$delivery_date.'</td>
					   </tr></tbody>
					</table>
					<p style="color: #888;"> Do you want to delete this borrowing? </p>
					<input type="hidden" id="userID" name="userID" value="'.$userID.'">
					<input type="hidden" id="bookID" name="bookID" value="'.$bookID.'">
					<input type="hidden" id="quantity" name="quantity" value="'.$quantity.'">
					<button type="submit">CONFIRM</button>
					<a href="list_loaned_books.php" class="cancel_button">CANCEL</a>
				</div>
			</div>
		</div>
	</form>';
?>
</html>