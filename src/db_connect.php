<?php
$host="localhost";
$db="lbls";
$user="root";
$pass="";
$conn=@mysqli_connect($host,$user,$pass, $db);

mysqli_set_charset($conn, "utf8");
$_SESSION["conn"] = $conn;
?>