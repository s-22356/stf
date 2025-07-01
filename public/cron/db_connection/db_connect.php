<?php
//MySQL credentials
$servername1 = "127.0.0.1:3306";
$username1   = "root";
$password1   = "";
$dbname1   = "user_sahajpath_db";

// Create connection
$conn = new mysqli($servername1, $username1, $password1, $dbname1);

// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		echo "<h5>DB " . $dbname1 . "&nbsp;Connected</h5>";
	}

//	die('2db');
?>