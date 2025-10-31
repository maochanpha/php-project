<?php
session_start();
require '../config/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Make sure ID exists in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: view_employee.php");
    exit;
}

$empID = (int) $_GET['id'];

try {
    // Delete employee (foreign key constraints will cascade to related tables)
    $stmt = $pdo->prepare("DELETE FROM Employee WHERE EmpID = ?");
    $stmt->execute([$empID]);

    // Redirect back after deletion
    header("Location: view_employee.php?msg=deleted");
    exit;

} catch (PDOException $e) {
    // If something goes wrong (e.g., foreign key issue)
    echo "<h3 style='color:red;text-align:center;'>Error deleting employee: " . htmlspecialchars($e->getMessage()) . "</h3>";
}
?>
