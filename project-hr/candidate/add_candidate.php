<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Condidate</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Add Condidate</h2>
        <form>
    <label>First Name</label><input type="text" name="fname" required>
    <label>Last Name</label><input type="text" name="lname" required>
    <label>Email</label><input type="email" name="email">
    <label>Phone</label><input type="text" name="phone">
    <label>Education</label><textarea name="education"></textarea>
    <button type="submit">Save</button>
        </form>
    </div>
</body>
</html>