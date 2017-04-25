<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>LBLS Login</title>

	<!-- Google Fonts -->
	<link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700|Lato:400,100,300,700,900' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="css/animate.css">
	<!-- Custom Stylesheet -->
	<link rel="stylesheet" href="css/style.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	
	<script> /* a script for capitalizing first letters of words - will be using in input validating */
		function toTitleCase(str) {
			var string = fixTurkishChars(str);
			return string.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
		}
		function fixTurkishChars(str) {
			var letters = { "ı": "i", "ş": "s", "ğ": "g", "ü": "u", "ö": "o", "ç": "c", "İ": "I", "Ş": "S", "Ğ": "G", "Ü": "U", "Ö": "O", "Ç": "C" };
			return str.replace(/(([ışğüöçİŞĞÜÖÇ]))/g, function(letter){ return letters[letter]; })
		}
	</script>
</head>

<body>
	<form action="login.php" method="POST">
		<div class="container">
			<div class="top">
				<h1 id="title" class="hidden"><span id="logo"> <span>LBLS</span></span></h1>
				<h3>LIBRARY BOOK LOAN SYSTEM</h3>
			
				<div class="login-box animated fadeInUp">
					<div class="box-header">
						<h2>Log In</h2>
					</div>
					<label for="username">Username</label>
					<br/>
					<input type="text" name="username" required="required" required pattern=.*\S.* onkeyup="this.value=toTitleCase(this.value)">
					<br/>
					<label for="password">Password</label>
					<br/>
					<input type="password" name="password" required="required" required pattern=.*\S.* onkeyup="this.value=toTitleCase(this.value)">
					<?php
						ob_start();
						include_once 'functions.php';
						if(isset($_SESSION["login"])) {
							if($_SESSION["login"] == "false") {
								echo '<p style="color:red">incorrect username/password!</p>';
							}
							else {
								/* something is wrong!! */
							}
						}
						ob_end_flush();
					?>
					<br/>
					<button type="submit">Sign In</button>
					<br/>
					<a href="index.php"><p class="small">Forgot your password?(Inactive)</p></a>
				</div>
			</div>
		</div>
	</form>
</body>

<script>
	$(document).ready(function () {
    	$('#logo').addClass('animated fadeInDown');
    	$("input:text:visible:first").focus();
	});
	$('#username').focus(function() {
		$('label[for="username"]').addClass('selected');
	});
	$('#username').blur(function() {
		$('label[for="username"]').removeClass('selected');
	});
	$('#password').focus(function() {
		$('label[for="password"]').addClass('selected');
	});
	$('#password').blur(function() {
		$('label[for="password"]').removeClass('selected');
	});
</script>

</html>