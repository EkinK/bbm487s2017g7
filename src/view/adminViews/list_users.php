<html>
<style>
	button.modify_btn {
		margin-top: 0px;
		border: 0;
		border-radius: 2px;
		color: white;
		padding: 5px;
		text-transform: uppercase;
		font-weight: 400;
		font-size: 0.9em;
		letter-spacing: 1px;
		background-color: #665851;
		cursor:pointer;
		outline: none;
	}
	
	button.modify_btn:hover {
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
	
	.box-header {
		margin-top: 0;
		border-radius: 5px 5px 0 0;
	}
	
	table.tbl:lang(tr) {
		border-radius: 8px;
	}
</style>

<?php 
	include_once 'admin_header.php';
?>
<link rel="stylesheet" href="css/animate.css">
<link rel="stylesheet" href="css/style.css">
<div class="container" style="padding-left: 15px;">
	<div class="top">
		<br/><br/><br/><br/><br/><br/><h1 id="title"></h1>
		<div class="animated fadeInLeft" style = "-webkit-animation-duration: 0.5s; -webkit-animation-delay: 0.5;">
			<div class="box-header">
				<h2 style="letter-spacing: 0px; font-size: 1.3em; padding-left: 5px;">USERS</h2>
			</div>
			<table class="tbl" style="border-radius: 8px;">
				<?php listUsers(1); /* 1 is the authority or normal users */ ?>
			</table>
		</div>
	</div>
</div>
</html>