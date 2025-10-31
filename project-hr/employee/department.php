<?php
session_start();
require '../config/db.php';
if (!isset($_SESSION['user'])) { header("Location: ../auth/login.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->prepare("INSERT INTO Department (DeptName, Location, Description) VALUES (?, ?, ?)");
  $stmt->execute([$_POST['DeptName'], $_POST['Location'], $_POST['Description']]);
}

$departments = $pdo->query("SELECT * FROM Department ORDER BY DepartmentID DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Departments</title>
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
      <li><a href="department.php" class="active">🏢 Departments</a></li>
      <li><a href="position.php">💼 Positions</a></li>
    </ul>
    <button class="logout-btn" onclick="window.location.href='../auth/logout.php'">Logout</button>
  </aside>

  <main class="content">
    <div class="topbar">
      <h1>Department List</h1>
      <button id="themeToggle">🌙</button>
    </div>

    <a href="#" id="openModal" class="add-btn">+ Add Department</a>

    <div class="table-box">
      <table>
        <tr><th>ID</th><th>Name</th><th>Location</th><th>Description</th></tr>
        <?php foreach($departments as $d): ?>
          <tr>
            <td><?= $d['DepartmentID'] ?></td>
            <td><?= htmlspecialchars($d['DeptName']) ?></td>
            <td><?= htmlspecialchars($d['Location']) ?></td>
            <td><?= htmlspecialchars($d['Description']) ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </main>
</div>

<!-- Modal -->
<div class="modal" id="modal">
  <div class="modal-content">
    <h3>Add Department</h3>
    <form method="post">
      <input name="DeptName" placeholder="Department Name" required>
      <input name="Location" placeholder="Location">
      <textarea name="Description" placeholder="Description"></textarea>
      <button type="submit">Save</button>
      <button type="button" id="closeModal" style="background:#ccc;color:#000;margin-left:10px;">Cancel</button>
    </form>
  </div>
</div>
</body>
</html>
