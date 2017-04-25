<html>

<?php include_once 'admin_header.php';?>
<link rel="stylesheet" href="css/animate.css">
<link rel="stylesheet" href="css/style.css">

<?php
	$title = $_POST['title'];
	$a_name = $_POST['a_name'];
	$a_surname = $_POST['a_surname'];
	$p_name = $_POST['p_name'];
	$quantity = $_POST['quantity'];

	echo '<form action="add_book.php" method="POST" lang="tr">
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
						  <td>: '.$a_name.' '.$a_surname.'</td>
					   </tr>
					   <tr>
						  <td>publisher</td>
						  <td>: '.$p_name.'</td>
					   </tr>
					   <tr>
						  <td>Quantity</td>
						  <td>: '.$quantity.'</td>
					   </tr></tbody>
					</table>
					<p style="color: #888;"> Do you want to proceed? </p>
					<input type="hidden" id="bookID" name="title" value="'.$title.'">
					<input type="hidden" id="bookID" name="a_name" value="'.$a_name.'">
					<input type="hidden" id="bookID" name="a_surname" value="'.$a_surname.'">
					<input type="hidden" id="bookID" name="p_name" value="'.$p_name.'">
					<input type="hidden" id="bookID" name="quantity" value="'.$quantity.'">
					<button type="submit">CONFIRM</button>
					<a href="add_book_page.php" class="cancel_button">CANCEL</a>
				</div>
			</div>
		</div>
	</form>';
?>
</html>