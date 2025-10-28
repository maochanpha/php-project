<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user";

if ($conn = new mysqli ($servername, $username, $password, $dbname)) {
    // Connection successful
} else {
    die("Connection failed: " . $conn->connect_error);
}
?>
