<?php include 'db.php';
$id = $_GET['id']; $s = $_GET['s'];
$conn->query("UPDATE EmpLeave SET Status='$s' WHERE LeaveID=$id");
header('Location: leaves.php');
?>
