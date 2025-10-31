<?php
session_start();
require '../config/db.php';
if (!isset($_SESSION['user'])) { header("Location: ../auth/login.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->prepare("INSERT INTO ContractTbl (EmpID, RecordDate, StartDate, EndDate, DName, PName, Salary, Description)
                         VALUES (?, NOW(), ?, ?, ?, ?, ?, ?)");
  $stmt->execute([
    $_POST['EmpID'], $_POST['StartDate'], $_POST['EndDate'], $_POST['DName'], $_POST['PName'], $_POST['Salary'], $_POST['Description']
  ]);
}

$contracts = $pdo->query("SELECT c.ContractID, e.FirstName, e.LastName, c.StartDate, c.EndDate, c.Salary
                          FROM ContractTbl c 
                          LEFT JOIN Employee e ON e.EmpID = c.EmpID 
                          ORDER BY c.ContractID DESC")->fetchAll();
$employees = $pdo->query("SELECT EmpID, FirstName, LastName FROM Employee")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Contracts</title>
<link rel="stylesheet" href="../public/css/style.css">
<script src="../public/js/modal.js" defer></script>
</head>
<body>
<div class="layout">
  <aside class="sidebar">
    <h2 class="logo">HR<span>System</span></h2>
    <ul>
      <li><a href="../index.php">🏠 Dashboard</a></li>
      <li><a href="view_employee.php">👥 Employees</a></li>
      <li><a href="contract.php" class="active">📃 Contracts</a></li>
    </ul>
    <button class="logout-btn" onclick="window.location.href='../auth/logout.php'">Logout</button>
  </aside>

  <main class="content">
    <div class="topbar">
      <h1>Contracts</h1>
      <button id="themeToggle">🌙</button>
    </div>

    <a href="#" id="openModal" class="add-btn">+ Add Contract</a>

    <div class="table-box">
      <table>
        <tr><th>ID</th><th>Employee</th><th>Start</th><th>End</th><th>Salary</th></tr>
        <?php foreach($contracts as $c): ?>
        <tr>
          <td><?= $c['ContractID'] ?></td>
          <td><?= $c['FirstName'].' '.$c['LastName'] ?></td>
          <td><?= $c['StartDate'] ?></td>
          <td><?= $c['EndDate'] ?></td>
          <td>$<?= number_format($c['Salary'],2) ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </main>
</div>

<!-- Modal -->
<div class="modal" id="modal">
  <div class="modal-content">
    <h3>Add Contract</h3>
    <form method="post">
      <select name="EmpID">
        <?php foreach($employees as $e): ?>
          <option value="<?= $e['EmpID'] ?>"><?= $e['FirstName'].' '.$e['LastName'] ?></option>
        <?php endforeach; ?>
      </select>
      <input type="date" name="StartDate" required>
      <input type="date" name="EndDate" required>
      <input name="DName" placeholder="Department Name">
      <input name="PName" placeholder="Position Name">
      <input type="number" name="Salary" step="0.01" placeholder="Salary">
      <textarea name="Description" placeholder="Description"></textarea>
      <button type="submit">Save</button>
      <button type="button" id="closeModal" style="background:#ccc;color:#000;margin-left:10px;">Cancel</button>
    </form>
  </div>
</div>
</body>
</html>
