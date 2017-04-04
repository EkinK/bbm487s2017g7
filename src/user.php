<html>

	<meta charset="utf-8">
	
	<link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700|Lato:400,100,300,700,900' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="css/animate.css">
	<!-- Custom Stylesheet -->
	<link rel="stylesheet" href="css/style.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	
</html>

<body>

	<?php 
	 
	include_once 'db_connect.php';

	ob_start();
	session_start();
	 
	if(!isset($_SESSION["login"])){
		header("Location:index.php");
	}
	else {
	/*	echo '<div class= "container"><div class="top">
				<center><h1 id="title" class="hidden"><span id="logo"> <span>Admin sayfasina hosgeldiniz..!</span></span></h1>
				<center><a href=index.php><p class="small">Guvenli cikis</p></a>
			</div></div>
			';*/
	}
	?>
	<div class= "container"><div class="top">
				<center><h1 id="title" class="hidden"><span id="logo"> <span>This is user page!</span></span></h1>
				<center><a href=index.php><p class="small">Log out</p></a>
			</div></div>
</body>