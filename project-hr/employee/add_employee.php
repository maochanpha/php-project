<?php
session_start();
require '../config/db.php';
if (!isset($_SESSION['user'])) { header("Location: ../auth/login.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sql = "INSERT INTO Employee (FirstName, LastName, Gender, Email, HPhone, DepartmentID, PstID, Salary, DateApplied)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    $_POST['FirstName'],
    $_POST['LastName'],
    $_POST['Gender'],
    $_POST['Email'],
    $_POST['HPhone'],
    $_POST['DepartmentID'],
    $_POST['PstID'],
    $_POST['Salary']
  ]);
  header("Location: view_employee.php");
  exit;
}

$departments = $pdo->query("SELECT * FROM Department")->fetchAll();
$positions = $pdo->query("SELECT * FROM PositionTbl")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Add Employee</title>
  <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
<div class="container">
  <h2>Add Employee</h2>
  <form method="post">
    <label>First Name</label>
    <input name="FirstName" required>

    <label>Last Name</label>
    <input name="LastName" required>

    <label>Gender</label>
    <select name="Gender">
      <option value="M">Male</option>
      <option value="F">Female</option>
      <option value="Other">Other</option>
    </select>

    <label>Email</label>
    <input type="email" name="Email">

    <label>Phone</label>
    <input type="text" name="HPhone">

    <label>Department</label>
    <select name="DepartmentID">
      <?php foreach ($departments as $d): ?>
        <option value="<?=$d['DepartmentID']?>"><?=$d['DeptName']?></option>
      <?php endforeach; ?>
    </select>

    <label>Position</label>
    <select name="PstID">
      <?php foreach ($positions as $p): ?>
        <option value="<?=$p['PstID']?>"><?=$p['PstName']?></option>
      <?php endforeach; ?>
    </select>

    <label>Salary</label>
    <input type="number" step="0.01" name="Salary">

    <button type="submit">Save</button>
  </form>
</div>
</body>
</html>
