<?php
$servername = "localhost";   // usually localhost
$username   = "root";        // default XAMPP user
$password   = "";            // leave empty unless you set one
$dbname     = "hr_management";  // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
