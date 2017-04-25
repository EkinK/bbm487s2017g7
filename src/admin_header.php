<html>
<meta charset="utf-8">
<?php
include_once 'functions.php';
?>
<style>
.dropbtn {
    background-color: #665851;
    color: white;
    padding: 14px;
    font-size: 12px;
    border: none;
	text-transform: uppercase;
	font-weight: 500;
	border-radius: 6px;
}

.homebtn {
    background-color: #665851;
    border: none;
	padding-left: 14px;
	width: 40px;
}

.dropbtn a {
    color: white;
    text-decoration: none;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #665851;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
	color: white;
	cursor: pointer;
	border: none;
	border-radius: 6px;
}

.dropdown-content a {
    color: white;
    padding: 12px 14px;
    text-decoration: none;
    display: block;
	text-transform: uppercase;
	border-radius: 6px;
}

.dropdown-content:hover a:hover{
	background-color: #aeaeae;
	opacity: 0.7;
	transition: 0.5s;
}

.dropdown:hover .dropdown-content {
    display: block;

}

.dropdown:hover .dropbtn {
    background-color: #aeaeae;
	color: #000000;
	opacity: 0.7;
	transition: 0.5s;
}

h2 {
	color: white;
	font-weight: 500;
	letter-spacing: 1px;
	font-size: 1.4em;
	line-height: 2.8em;
}

body {
	/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#0e0e0e+0,7d7e7d+80 */
	background: #3e3e3e; /* Old browsers */
	background: -moz-linear-gradient(top, #3e3e3e 0%, #9d9e9d 80%); /* FF3.6-15 */
	background: -webkit-linear-gradient(top, #3e3e3e 0%,#9d9e9d 80%); /* Chrome10-25,Safari5.1-6 */
	background: linear-gradient(to bottom, #3e3e3e 0%,#9d9e9d 80%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#3e3e3e', endColorstr='#9d9e9d',GradientType=0 ); /* IE6-9 */
	background-repeat: no-repeat;
    background-attachment: fixed;
}

.navigator {
	background-color: #665851;
	max-width: 750px;
	float: left;
	border-radius: 6px;
	border: none;
	-webkit-transform: translate(3%, 0%);
	position: absolute;
	
}
.search_box {
	background-color: red;
}

.search_box input[type=text] {
	width: 200px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 7px;
    font-size: 12px;
    background-color: white;
    background-position: 4px 4px; 
    background-repeat: no-repeat;
    padding: 6px 6px 6px 20px;
    -webkit-transition: width 0.4s ease-in-out;
    transition: width 0.4s ease-in-out;
	display: inline-block;
	position: relative;
	font-style: italic;
}

/* When the input field gets focus, change its width to 100% */
.search_box input[type=text]:focus {
    width: 250px;
}

</style>
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
<head>
<link rel="stylesheet" href="css/animate.css">
<link rel="stylesheet" href="css/style.css">
	<?php
	
		
		
		if(!isset($_SESSION["login"]) || ($_SESSION["login"] == "false")) { /* check if user logged in */
			header("Location:index.php");
		}
		
		$name = $_SESSION["name"];
		echo '<h2 style="padding-left: 20px;">Library Book Loan System (LBLS)
					<div style="float: right; font-size: 12px; padding-top: 10px; padding-right: 60px;">Welcome 
						<a style= "color: white">" '.$name.' "</a>
					
					<div style="text-decoration = underline; float: right; font-size: 12px; padding-left: 30px;">
						<a href="logout.php" style= "font-style: italic; color: #aeaeae">Log Out</a>
					
			</h2>';
	?>
	<div class="navigator"> 
		<div class="dropdown">
		  <button class="homebtn"><a href="admin.php"><img src="images/home-icon.png" style="max-height: 22px; -webkit-transform: translate(-60%, -68%); position: absolute;"></a></button>
		</div>
		<div class="dropdown">
		  <button class="dropbtn">Show Books</button>
		  <div class="dropdown-content" style="text">
			<a href="search_book.php">Lıbrary Books</a>
			<a href="list_loaned_books.php">Loaned Books</a>
		  </div>
		</div>
		<div class="dropdown">
		  <button class="dropbtn">Show Users</button>
		  <div class="dropdown-content" style="text">
			<a href="list_users.php">Normal Users</a>
			<a href="list_staffs.php">Staffs</a>
		  </div>
		</div>
		<div class="dropdown">
		  <button class="dropbtn">Insert</button>
			<div class="dropdown-content" style="text">
				<a href="add_book_page.php">Insert Book</a>
				<a href="add_user_page.php">Insert User</a>
			</div>
		</div>
	</div>
	<div class="search_box">
		<form style="float:right; padding-top: 8px; padding-right: 50px;" action="search_book.php" method="POST"> 
			<div style="background-color: #777; border: none; border-radius: 6px; padding: 4px; -webkit-transform: translate(0%, -10%);">
				<label style="font-size: 13px; color: white; padding-right: 9px; padding-left: 6px;">Search Books</label>
				<input type="text" name="search" placeholder="Title or Author Name" onkeyup="this.value=fixTurkishChars(this.value)">
			</div>
		</form>
	</div>

</head>
</html>