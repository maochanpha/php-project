<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['EmpID']) || !is_numeric($_GET['EmpID'])) {
    header("Location: employee_list.php?msg=error");
    exit();
}

$EmpID = intval($_GET['EmpID']);

$conn = new mysqli("localhost", "root", "", "hr_management");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// 🔹 First delete dependents
$sql_dep = "DELETE FROM dependents WHERE EmpID = ?";
$stmt_dep = $conn->prepare($sql_dep);
$stmt_dep->bind_param("i", $EmpID);
$stmt_dep->execute();
$stmt_dep->close();

// 🔹 Then delete employee
$sql_emp = "DELETE FROM Employee WHERE EmpID = ?";
$stmt_emp = $conn->prepare($sql_emp);
$stmt_emp->bind_param("i", $EmpID);

if ($stmt_emp->execute()) {
    if ($stmt_emp->affected_rows > 0) {
        header("Location: employee_list.php?msg=deleted");
    } else {
        header("Location: employee_list.php?msg=notfound");
    }
} else {
    header("Location: employee_list.php?msg=error");
}

$stmt_emp->close();
$conn->close();
exit();
?>
