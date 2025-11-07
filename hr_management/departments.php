<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user'])) header('Location: login.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Departments</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="main">
  <?php include 'includes/header.php'; ?>
  <section class="content">
    <h3>Departments</h3>
    <a href="add_department.php" class="btn">➕ Add Department</a>
    <table>
      <tr><th>ID</th><th>Name</th><th>Location</th><th>Action</th></tr>
      <?php
      $rows = $conn->query("SELECT * FROM Department");
      while ($d = $rows->fetch_assoc()) {
        echo "<tr>
          <td>{$d['DepartmentID']}</td>
          <td>{$d['DeptName']}</td>
          <td>{$d['Location']}</td>
          <td>
            <a href='edit_department.php?id={$d['DepartmentID']}'>✏️</a> |
            <a href='delete_department.php?id={$d['DepartmentID']}' onclick='return confirm(\"Delete?\")'>🗑️</a>
          </td>
        </tr>";
      }
      ?>
    </table>
  </section>
</div>
</body>
</html>
