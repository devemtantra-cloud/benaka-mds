<?php
// Copy this file to database.php (same folder) and fill in the real values.
// database.php is git-ignored: never commit real credentials.
$hostname="localhost";
$username="YOUR_DB_USER";
$password="YOUR_DB_PASSWORD";
$db="YOUR_DB_NAME";
$con=mysqli_connect($hostname,$username,$password,$db) or die("error connecting db");
?>
