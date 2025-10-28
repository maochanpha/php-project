<?php
$host = 'localhost';
$root = 'root';
$pass = '';
$dbname = 'test';
$conn = new mysqli($host, $root, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>