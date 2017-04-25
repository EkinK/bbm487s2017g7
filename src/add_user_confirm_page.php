<html>

<?php include_once 'admin_header.php';?>
<link rel="stylesheet" href="css/animate.css">
<link rel="stylesheet" href="css/style.css">

<?php

$username = $_POST['username'];
$pass = $_POST['password'];
$name = $_POST['name'];
$surname = $_POST['surname'];
$authority = $_POST['user_type'];

echo '<form action="add_user.php" method="POST" lang="tr">
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
					  <td>Password</td>
					  <td>: '.$pass.'</td>
				   </tr>
				   <tr>
					  <td>Name</td>
					  <td>: '.$name.'</td>
				   </tr>
				   <tr>
					  <td>Surname</td>
					  <td>: '.$surname.'</td>
				   </tr></tbody>
				</table>
				<p style="color: #888;"> Do you want to proceed? </p>
				<input type="hidden" id="bookID" name="username" value="'.$username.'">
				<input type="hidden" id="bookID" name="password" value="'.$pass.'">
				<input type="hidden" id="bookID" name="name" value="'.$name.'">
				<input type="hidden" id="bookID" name="surname" value="'.$surname.'">
				<input type="hidden" id="bookID" name="authority" value="'.$authority.'">
				<button type="submit">CONFIRM</button>
				<a href="add_user_page.php" class="cancel_button">CANCEL</a>
			</div>
		</div>
	</div>
</form>';

?>
</html>