<?php
session_start();
require 'config/db.php';
if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HR Management Dashboard</title>
  <link rel="stylesheet" href="public/css/style.css">
  <script src="public/js/modal.js" defer></script>
</head>
<body>
<div class="layout">

  <!-- Sidebar -->
  <aside class="sidebar">
    <h2 class="logo">HR<span>System</span></h2>
    <ul>
      <li><a href="index.php" class="active">🏠 Dashboard</a></li>
      <li><a href="employee/view_employee.php">👥 Employees</a></li>
      <li><a href="employee/department.php">🏢 Departments</a></li>
      <li><a href="employee/position.php">💼 Positions</a></li>
      <li><a href="employee/contract.php">📃 Contracts</a></li>
      <li><a href="employee/leave.php">🕒 Leave</a></li>
      <li><a href="reports/employee_report.php">📊 Reports</a></li>
    </ul>
    <button class="logout-btn" onclick="window.location.href='auth/logout.php'">Logout</button>
  </aside>

  <!-- Main -->
  <main class="content">
    <header class="topbar">
      <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['username']); ?> 👋</h1>
      <button id="themeToggle">🌙</button>
    </header>

    <section class="cards">
      <div class="card"><h3>Employees</h3><p><?= $pdo->query("SELECT COUNT(*) FROM Employee")->fetchColumn(); ?></p></div>
      <div class="card"><h3>Departments</h3><p><?= $pdo->query("SELECT COUNT(*) FROM Department")->fetchColumn(); ?></p></div>
      <div class="card"><h3>Positions</h3><p><?= $pdo->query("SELECT COUNT(*) FROM PositionTbl")->fetchColumn(); ?></p></div>
      <div class="card"><h3>Contracts</h3><p><?= $pdo->query("SELECT COUNT(*) FROM ContractTbl")->fetchColumn(); ?></p></div>
    </section>
  </main>

</div>
</body>
</html>
