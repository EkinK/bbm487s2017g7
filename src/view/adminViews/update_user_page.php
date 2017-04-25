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

	echo '<form action="update_user.php" method="POST" lang="tr">
		<div class="container">
			<div class="top">
				<br/><br/><br/><br/><br/><h1 id="title"></h1>
				<div class="login-box animated fadeInUp" style="max-width: 40%;">
					<div class="box-header">
						<h2 style="letter-spacing: 0px; font-size: 1.3em;">UPDATE USER</h2>
					</div>
					<table class="tbl">
						<tr>
							<th>Field</th>
							<th>Original Value</th>
							<th>Change As</th>
						</tr>
					   <tbody>
					   <tr>
						  <td>Username</td>
						  <td>: '.$username.'</td>
						  <td><input type="text" name="username" onkeyup="this.value=toTitleCase(this.value)" 
						  style="border-radius: 4px;width: 200px; margin-bottom: 5px; -webkit-transform: translate(0%, 10%);"></td>
					   </tr>
					   <tr>
						  <td>Password</td>
						  <td>: '.$password.'</td>
						  <td><input type="text" name="password" onkeyup="this.value=toTitleCase(this.value)" placeholder="will be encrypted"
						  style="font-style: italic; border-radius: 4px;width: 200px; margin-bottom: 5px; -webkit-transform: translate(0%, 10%);"></td>
					   </tr>
					   <tr>
						  <td>Name</td>
						  <td>: '.$name.'</td>
						  <td><input type="text" name="name" onkeyup="this.value=toTitleCase(this.value)" 
						  style="border-radius: 4px;width: 200px; margin-bottom: 5px; -webkit-transform: translate(0%, 10%);"></td>
					   </tr>
					   <tr>
						  <td>Surname</td>
						  <td>: '.$surname.'</td>
						  <td><input type="text" name="surname" onkeyup="this.value=toTitleCase(this.value)" 
						  style="border-radius: 4px;width: 200px; margin-bottom: 5px; -webkit-transform: translate(0%, 10%);"></td>
					   </tr>
					   </tbody>
					</table>
					<p style="color: #888;"> Do you want to proceed? </p>
					<input type="hidden" id="userID" name="userID" value="'.$userID.'">
					<input type="hidden" id="old_username" name="old_username" value="'.$username.'">
					<input type="hidden" id="old_password" name="old_password" value="'.$password.'">
					<input type="hidden" id="old_name" name="old_name" value="'.$name.'">
					<input type="hidden" id="old_surname" name="old_surname" value="'.$surname.'">
					<button type="submit">UPDATE</button>
					<a href="search_book.php" class="cancel_button">CANCEL</a>
				</div>
			</div>
		</div>
	</form>';
?>
</html>