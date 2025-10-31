<?php
session_start();
require '../config/db.php';
if (!isset($_SESSION['user'])) { header("Location: ../auth/login.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->prepare("INSERT INTO Training (EmpID, SchName, CusName, StartDate, EndDate, Support, Grade, Description)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->execute([$_POST['EmpID'], $_POST['SchName'], $_POST['CusName'], $_POST['StartDate'], $_POST['EndDate'], $_POST['Support'], $_POST['Grade'], $_POST['Description']]);
}
$employees = $pdo->query("SELECT EmpID, FirstName, LastName FROM Employee")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Training</title>
  <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
<div class="container">
  <h2>Employee Training</h2>
  <form method="post">
    <label>Employee</label>
    <select name="EmpID">
      <?php foreach($employees as $e): ?>
      <option value="<?=$e['EmpID']?>"><?=$e['FirstName']?> <?=$e['LastName']?></option>
      <?php endforeach; ?>
    </select>
    <label>School Name</label><input name="SchName">
    <label>Course Name</label><input name="CusName">
    <label>Start Date</label><input type="date" name="StartDate">
    <label>End Date</label><input type="date" name="EndDate">
    <label>Support</label><input name="Support">
    <label>Grade</label><input name="Grade">
    <label>Description</label><textarea name="Description"></textarea>
    <button>Save</button>
  </form>
</div>
</body>
</html>
