<?php
$servername = "localhost:3307";
$username = "root"; // Update with your username
$password = ""; // Update with your password
$dbname = "user_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
