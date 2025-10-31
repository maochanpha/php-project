<?php
session_start();
require '../config/db.php';
if (!isset($_SESSION['user'])) { header("Location: ../auth/login.php"); exit; }

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: shortlist.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->prepare("INSERT INTO Interview (EmpID, InterviewID, InterviewDate, ExpSal, Result, Comments)
                         VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->execute([$id, $_POST['InterviewID'], $_POST['InterviewDate'], $_POST['ExpSal'], $_POST['Result'], $_POST['Comments']]);
  header("Location: shortlist.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Interview</title>
  <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
<div class="container">
  <h2>Interview Form</h2>
  <form method="post">
    <label>Interview ID</label><input name="InterviewID" required>
    <label>Date</label><input type="date" name="InterviewDate" required>
    <label>Expected Salary</label><input type="number" name="ExpSal" step="0.01">
    <label>Result</label><input name="Result">
    <label>Comments</label><textarea name="Comments"></textarea>
    <button type="submit">Save</button>
  </form>
</div>
</body>
</html>
