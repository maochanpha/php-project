<?php 
$host = 'localhost';
$db   = 'hr_management';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // In production, you should log this instead of displaying it
    die('Database connection failed: ' . $e->getMessage());
}
?>
