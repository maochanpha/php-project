<?php
require '../config/db.php';
$employees = $pdo->query("SELECT EmpID, FirstName, LastName, Gender, Email, Salary FROM Employee")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Employee Report</title>
<link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
<div class="container">
  <h2>Employee Report</h2>
  <table>
    <tr><th>ID</th><th>Name</th><th>Gender</th><th>Email</th><th>Salary</th></tr>
    <?php foreach($employees as $e): ?>
      <tr>
        <td><?=$e['EmpID']?></td>
        <td><?=$e['FirstName']?> <?=$e['LastName']?></td>
        <td><?=$e['Gender']?></td>
        <td><?=$e['Email']?></td>
        <td><?=$e['Salary']?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
</body>
</html>
