<html>

<?php include_once 'admin_header.php';?>

<link rel="stylesheet" href="css/animate.css">
<link rel="stylesheet" href="css/style.css">

	<form action="add_user_confirm_page.php" method="POST">
		<div class="container">
			<div class="top">
				<br/><br/><br/><h1 id="title"></h1>
				<div class="login-box animated fadeInUp" style="max-width: 330px;">
					<div class="box-header">
						<h2 style="letter-spacing: 0px; font-size: 1.3em;">INSERT USER</h2>
					</div>
					<table class="tbl">
					   <tbody><tr>
						  <td>Username</td>
						  <td><input type="text" name="username" required="required" onBlur="this.value=toTitleCase(this.value)" style="border-radius: 4px;width: 200px; margin-bottom: 5px; -webkit-transform: translate(0%, 10%);"></td>
					   </tr>
					   <tr>
						  <td>Password</td>
						  <td><input type="text" name="password" required="required" onBlur="this.value=toTitleCase(this.value)" style="border-radius: 4px;width: 200px; margin-bottom: 5px; -webkit-transform: translate(0%, 10%);"></td>
					   </tr>
					   <tr>
						  <td>Name</td>
						  <td><input type="text" name="name" required="required" onBlur="this.value=toTitleCase(this.value)" style="border-radius: 4px;width: 200px; margin-bottom: 5px; -webkit-transform: translate(0%, 10%);"></td>
					   </tr>
					   <tr>
						  <td>Surname</td>
						  <td><input type="text" name="surname" required="required" onBlur="this.value=toTitleCase(this.value)" style="border-radius: 4px;width: 200px; margin-bottom: 5px; -webkit-transform: translate(0%, 10%);"></td>
					   </tr>
					   <tr>
						  <td>User Type</td>
						  <td>
							<input type="radio" name="user_type" value="1"> Normal User<br>
							<input type="radio" name="user_type" value="2"> Staff
						  </td>
					   </tr></tbody>
					</table>
					<button type="submit" style="letter-spacing: 0px;">INSERT</button>
				</div>
			</div>
		</div>
	</form>
</html>