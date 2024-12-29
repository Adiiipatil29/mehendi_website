<?php
$servername = "localhost";
$db_username = "root";
$db_password = ""; // Use an empty string if the root user has no password
$dbname = "user";

// Create connection
$conn = mysqli_connect($servername, $db_username, $db_password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>