<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include 'config/db.php';
?>

<?php include 'includes/header.php'; ?>
<h1>Welcome to HR Management System</h1>
<div class="dashboard">
    <a href="pages/employees/index.php">Manage Employees</a>
    <a href="pages/departments/index.php">Manage Departments</a>
    <a href="pages/positions/index.php">Manage Positions</a>
    <a href="logout.php">Logout</a>
</div>
<?php include 'includes/footer.php'; ?>
