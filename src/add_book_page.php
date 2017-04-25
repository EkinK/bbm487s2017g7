<html>

<?php include_once 'admin_header.php';?>

<link rel="stylesheet" href="css/animate.css">
<link rel="stylesheet" href="css/style.css">

	<form action="add_book_confirm.php" method="POST">
		<div class="container">
			<div class="top">
				<br/><br/><br/><h1 id="title"></h1>
				<div class="login-box animated fadeInUp" style="max-width: 330px;">
					<div class="box-header">
						<h2 style="letter-spacing: 0px; font-size: 1.3em;">INSERT BOOK</h2>
					</div>
					<table class="tbl">
					   <tbody><tr>
						  <td>Title</td>
						  <td><input type="text" name="title" required="required" onkeyup="this.value=toTitleCase(this.value)" style="border-radius: 4px;width: 200px; margin-bottom: 5px; -webkit-transform: translate(0%, 10%);"></td>
					   </tr>
					   <tr>
						  <td>Author Name</td>
						  <td><input type="text" name="a_name" required="required" onkeyup="this.value=toTitleCase(this.value)" style="border-radius: 4px;width: 200px; margin-bottom: 5px; -webkit-transform: translate(0%, 10%);"></td>
					   </tr>
					   <tr>
						  <td>Author Surname</td>
						  <td><input type="text" name="a_surname" required="required" onkeyup="this.value=toTitleCase(this.value)" style="border-radius: 4px;width: 200px; margin-bottom: 5px; -webkit-transform: translate(0%, 10%);"></td>
					   </tr>
					   <tr>
						  <td>Publisher</td>
						  <td><input type="text" name="p_name" required="required" onkeyup="this.value=toTitleCase(this.value)" style="border-radius: 4px;width: 200px; margin-bottom: 5px; -webkit-transform: translate(0%, 10%);"></td>
					   </tr>
					   <tr>
						  <td>Quantity</td>
						  <td><input type="number" name="quantity" min="1" required="required" style="border-radius: 4px;width: 200px; margin-bottom: 5px; -webkit-transform: translate(0%, 10%);"></td>
					   </tr></tbody>
					</table>
					<button type="submit" style="letter-spacing: 0px;">INSERT</button>
				</div>
			</div>
		</div>
	</form>
</html>