<?php
session_start();
require '../config/db.php';
if (!isset($_SESSION['user'])) { header("Location: ../auth/login.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->prepare("INSERT INTO PositionTbl (PstName, minSal, maxSal, Description) VALUES (?, ?, ?, ?)");
  $stmt->execute([$_POST['PstName'], $_POST['minSal'], $_POST['maxSal'], $_POST['Description']]);
}
$positions = $pdo->query("SELECT * FROM PositionTbl ORDER BY PstID DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Positions</title>
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
      <li><a href="department.php">🏢 Departments</a></li>
      <li><a href="position.php" class="active">💼 Positions</a></li>
    </ul>
    <button class="logout-btn" onclick="window.location.href='../auth/logout.php'">Logout</button>
  </aside>

  <main class="content">
    <div class="topbar">
      <h1>Position List</h1>
      <button id="themeToggle">🌙</button>
    </div>

    <a href="#" id="openModal" class="add-btn">+ Add Position</a>

    <div class="table-box">
      <table>
        <tr><th>ID</th><th>Name</th><th>Min Salary</th><th>Max Salary</th><th>Description</th></tr>
        <?php foreach($positions as $p): ?>
          <tr>
            <td><?= $p['PstID'] ?></td>
            <td><?= htmlspecialchars($p['PstName']) ?></td>
            <td>$<?= number_format($p['minSal'], 2) ?></td>
            <td>$<?= number_format($p['maxSal'], 2) ?></td>
            <td><?= htmlspecialchars($p['Description']) ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </main>
</div>

<!-- Modal -->
<div class="modal" id="modal">
  <div class="modal-content">
    <h3>Add Position</h3>
    <form method="post">
      <input name="PstName" placeholder="Position Name" required>
      <input name="minSal" type="number" step="0.01" placeholder="Min Salary">
      <input name="maxSal" type="number" step="0.01" placeholder="Max Salary">
      <textarea name="Description" placeholder="Description"></textarea>
      <button type="submit">Save</button>
      <button type="button" id="closeModal" style="background:#ccc;color:#000;margin-left:10px;">Cancel</button>
    </form>
  </div>
</div>
</body>
</html>
