<html>

<?php include_once 'admin_header.php';?>
<link rel="stylesheet" href="css/animate.css">
<link rel="stylesheet" href="css/style.css">

<?php
	$title = $_POST['title'];
	$a_name = $_POST['a_name'];
	$a_surname = $_POST['a_surname'];
	$publisher = $_POST['publisher'];
	$quantity = $_POST['quantity'];
	$bookID = $_POST['bookID'];

	echo '<form action="update_book.php" method="POST" lang="tr">
		<div class="container">
			<div class="top">
				<br/><br/><br/><br/><br/><h1 id="title"></h1>
				<div class="login-box animated fadeInUp" style="max-width: 35%;">
					<div class="box-header">
						<h2 style="letter-spacing: 0px; font-size: 1.3em;">UPDATE BOOK</h2>
					</div>
					<table class="tbl">
						<tr>
							<th>Field</th>
							<th>Original Value</th>
							<th>Change As</th>
						</tr>
					   <tbody>
					   <tr>
						  <td>Title</td>
						  <td>: '.$title.'</td>
						  <td><input type="text" name="title" onBlur="this.value=toTitleCase(this.value)" 
						  style="border-radius: 4px;width: 200px; margin-bottom: 5px; -webkit-transform: translate(0%, 10%);"></td>
					   </tr>
					   <tr>
						  <td>Author Name</td>
						  <td>: '.$a_name.'</td>
						  <td><input type="text" name="a_name" onBlur="this.value=toTitleCase(this.value)" 
						  style="border-radius: 4px;width: 200px; margin-bottom: 5px; -webkit-transform: translate(0%, 10%);"></td>
					   </tr>
					   <tr>
						  <td>Author Surname</td>
						  <td>: '.$a_surname.'</td>
						  <td><input type="text" name="a_surname" onBlur="this.value=toTitleCase(this.value)" 
						  style="border-radius: 4px;width: 200px; margin-bottom: 5px; -webkit-transform: translate(0%, 10%);"></td>
					   </tr>
					   <tr>
						  <td>publisher</td>
						  <td>: '.$publisher.'</td>
						  <td><input type="text" name="publisher" onBlur="this.value=toTitleCase(this.value)" 
						  style="border-radius: 4px;width: 200px; margin-bottom: 5px; -webkit-transform: translate(0%, 10%);"></td>
					   </tr>
					   <tr>
						  <td>Quantity</td>
						  <td>: '.$quantity.'</td>
						  <td><input type="number" name="quantity" min="1" style="border-radius: 4px;width: 200px; margin-bottom: 5px; -webkit-transform: translate(0%, 10%);"></td>
					   </tr></tbody>
					</table>
					<p style="color: #888;"> Do you want to proceed? </p>
					<input type="hidden" id="bookID" name="bookID" value="'.$bookID.'">
					<input type="hidden" id="old_title" name="old_title" value="'.$title.'">
					<input type="hidden" id="old_a_name" name="old_a_name" value="'.$a_name.'">
					<input type="hidden" id="old_a_surname" name="old_a_surname" value="'.$a_surname.'">
					<input type="hidden" id="old_quantity" name="old_quantity" value="'.$quantity.'">
					<input type="hidden" id="old_publisher" name="old_publisher" value="'.$publisher.'">
					<button type="submit">UPDATE</button>
					<a href="search_book.php" class="cancel_button">CANCEL</a>
				</div>
			</div>
		</div>
	</form>';
?>
</html>