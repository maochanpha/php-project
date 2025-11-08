<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: departments.php?msg=error");
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM Department WHERE DepartmentID = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        header("Location: departments.php?msg=deleted");
    } else {
        header("Location: departments.php?msg=error");
    }
} else {
    header("Location: departments.php?msg=error");
}

$stmt->close();
$conn->close();
exit();
?>
