<?php
session_start();
include "db.php";

if ($conn->query("TRUNCATE TABLE students")) {
    $_SESSION['message'] = "All data cleared successfully.";
} else {
    $_SESSION['message'] = "Error clearing data: " . $conn->error;
}
header("Location: result.php");
exit();
?>