<?php
session_start();
require '../config/db.php';
if (!isset($_SESSION['user'])) { header("Location: ../auth/login.php"); exit; }

$candidates = $pdo->query("SELECT EmpID, FirstName, LastName, PositionApply, Email FROM Employee WHERE SortList = 1")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Candidate Shortlist</title>
  <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
<div class="container">
  <h2>Shortlisted Candidates</h2>
  <table>
    <tr><th>ID</th><th>Name</th><th>Position</th><th>Email</th><th>Action</th></tr>
    <?php foreach ($candidates as $c): ?>
      <tr>
        <td><?=$c['EmpID']?></td>
        <td><?=$c['FirstName']?> <?=$c['LastName']?></td>
        <td><?=$c['PositionApply']?></td>
        <td><?=$c['Email']?></td>
        <td><a href="interview.php?id=<?=$c['EmpID']?>">Interview</a></td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
</body>
</html>
