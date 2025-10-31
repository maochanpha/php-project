<?php
session_start();
require '../config/db.php';

// Check if logged in
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Make sure employee ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: view_employee.php");
    exit;
}

$empID = (int) $_GET['id'];

// Fetch employee record
$stmt = $pdo->prepare("SELECT * FROM Employee WHERE EmpID = ?");
$stmt->execute([$empID]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    die("<h3 style='color:red;text-align:center;'>Employee not found!</h3>");
}

// Fetch department and position lists
$departments = $pdo->query("SELECT * FROM Department")->fetchAll();
$positions = $pdo->query("SELECT * FROM PositionTbl")->fetchAll();

// Handle update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "UPDATE Employee 
            SET FirstName = ?, LastName = ?, Gender = ?, Email = ?, HPhone = ?, 
                DepartmentID = ?, PstID = ?, Salary = ?
            WHERE EmpID = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['FirstName'],
        $_POST['LastName'],
        $_POST['Gender'],
        $_POST['Email'],
        $_POST['HPhone'],
        $_POST['DepartmentID'],
        $_POST['PstID'],
        $_POST['Salary'],
        $empID
    ]);

    header("Location: view_employee.php?msg=updated");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Edit Employee</title>
  <link rel="stylesheet" href="../public/css/style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f8f9fa;
      margin: 0;
      padding: 0;
    }
    .container {
      width: 60%;
      margin: 40px auto;
      background: white;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      padding: 25px;
    }
    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }
    form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
    }
    label {
      font-weight: bold;
    }
    input, select {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      grid-column: span 2;
      background: #2e86de;
      color: white;
      border: none;
      padding: 10px;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.3s;
    }
    button:hover {
      background: #2169c9;
    }
    .back-link {
      display: block;
      text-align: center;
      margin-top: 15px;
      color: #2e86de;
      text-decoration: none;
    }
  </style>
</head>
<body>
<div class="container">
  <h2>Edit Employee</h2>
  <form method="post">
    <div>
      <label>First Name</label>
      <input name="FirstName" value="<?= htmlspecialchars($employee['FirstName']) ?>" required>
    </div>
    <div>
      <label>Last Name</label>
      <input name="LastName" value="<?= htmlspecialchars($employee['LastName']) ?>" required>
    </div>
    <div>
      <label>Gender</label>
      <select name="Gender">
        <option value="M" <?= $employee['Gender'] == 'M' ? 'selected' : '' ?>>Male</option>
        <option value="F" <?= $employee['Gender'] == 'F' ? 'selected' : '' ?>>Female</option>
        <option value="Other" <?= $employee['Gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
      </select>
    </div>
    <div>
      <label>Email</label>
      <input type="email" name="Email" value="<?= htmlspecialchars($employee['Email']) ?>">
    </div>
    <div>
      <label>Phone</label>
      <input type="text" name="HPhone" value="<?= htmlspecialchars($employee['HPhone']) ?>">
    </div>
    <div>
      <label>Department</label>
      <select name="DepartmentID">
        <?php foreach ($departments as $d): ?>
          <option value="<?= $d['DepartmentID'] ?>" <?= $d['DepartmentID'] == $employee['DepartmentID'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($d['DeptName']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label>Position</label>
      <select name="PstID">
        <?php foreach ($positions as $p): ?>
          <option value="<?= $p['PstID'] ?>" <?= $p['PstID'] == $employee['PstID'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($p['PstName']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label>Salary</label>
      <input type="number" step="0.01" name="Salary" value="<?= htmlspecialchars($employee['Salary']) ?>">
    </div>

    <button type="submit">Update Employee</button>
  </form>
  <a href="view_employee.php" class="back-link">← Back to Employee List</a>
</div>
</body>
</html>
