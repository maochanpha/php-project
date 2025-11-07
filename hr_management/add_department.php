<?php include 'db.php'; session_start(); if (!isset($_SESSION['user'])) header('Location: login.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['DeptName'];
  $loc = $_POST['Location'];
  $sql = "INSERT INTO Department (DeptName, Location) VALUES ('$name','$loc')";
  $conn->query($sql);
  header('Location: departments.php');
}
?>
<!DOCTYPE html>
<html><head><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'includes/sidebar.php'; ?><div class="main"><?php include 'includes/header.php'; ?>
<section class="content">
  <h3>Add Department</h3>
  <form method="POST">
    <label>Name:</label><input type="text" name="DeptName" required>
    <label>Location:</label><input type="text" name="Location" required>
    <button type="submit">Save</button>
  </form>
</section></div></body></html>
