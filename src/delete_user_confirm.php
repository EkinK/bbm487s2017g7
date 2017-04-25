<html>

<?php include_once 'admin_header.php';?>
<link rel="stylesheet" href="css/animate.css">
<link rel="stylesheet" href="css/style.css">

<?php
	$userID = $_POST['userID'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$name = $_POST['name'];
	$surname = $_POST['surname'];
	
	echo '<form action="delete_user.php" method="POST">
		<div class="container">
			<div class="top">
				<br/><br/><br/><br/><br/><h1 id="title"></h1>
				<div class="login-box animated fadeInUp" style="max-width: 25%;">
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
						  <td>password</td>
						  <td>: '.$password.'</td>
					   </tr>
					   <tr>
						  <td>name</td>
						  <td>: '.$name.'</td>
					   </tr>
					   <tr>
						  <td>surname</td>
						  <td>: '.$surname.'</td>
					   </tr></tbody>
					</table>
					<p style="color: #888;"> Do you want to delete this user? </p>
					<input type="hidden" id="userID" name="userID" value="'.$userID.'">
					<button type="submit">CONFIRM</button>
					<a href="list_users.php" class="cancel_button">CANCEL</a>
				</div>
			</div>
		</div>
	</form>';
?>
</html>