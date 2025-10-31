<?php
session_start();
require '../config/db.php';
if (!isset($_SESSION['user'])) { header("Location: ../auth/login.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->prepare("INSERT INTO EmpLeave (EmpID, LtyID, StartDate, EndDate, AmountDay, Description, Status)
                         VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->execute([
    $_POST['EmpID'], $_POST['LtyID'], $_POST['StartDate'], $_POST['EndDate'], $_POST['AmountDay'], $_POST['Description'], $_POST['Status']
  ]);
}
$employees = $pdo->query("SELECT EmpID, FirstName, LastName FROM Employee")->fetchAll();
$leaves = $pdo->query("SELECT * FROM LvEntitleMnt")->fetchAll();
$data = $pdo->query("SELECT el.EmpLeaveID, e.FirstName, e.LastName, el.StartDate, el.EndDate, el.AmountDay, el.Status 
                     FROM EmpLeave el 
                     LEFT JOIN Employee e ON e.EmpID = el.EmpID 
                     ORDER BY el.EmpLeaveID DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Leave Management</title>
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
      <li><a href="leave.php" class="active">🕒 Leave</a></li>
    </ul>
    <button class="logout-btn" onclick="window.location.href='../auth/logout.php'">Logout</button>
  </aside>

  <main class="content">
    <div class="topbar">
      <h1>Employee Leave</h1>
      <button id="themeToggle">🌙</button>
    </div>

    <a href="#" id="openModal" class="add-btn">+ Add Leave</a>

    <div class="table-box">
      <table>
        <tr><th>ID</th><th>Employee</th><th>Start</th><th>End</th><th>Days</th><th>Status</th></tr>
        <?php foreach($data as $d): ?>
        <tr>
          <td><?= $d['EmpLeaveID'] ?></td>
          <td><?= $d['FirstName'].' '.$d['LastName'] ?></td>
          <td><?= $d['StartDate'] ?></td>
          <td><?= $d['EndDate'] ?></td>
          <td><?= $d['AmountDay'] ?></td>
          <td><?= htmlspecialchars($d['Status']) ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </main>
</div>

<!-- Modal -->
<div class="modal" id="modal">
  <div class="modal-content">
    <h3>Add Leave</h3>
    <form method="post">
      <select name="EmpID">
        <?php foreach($employees as $e): ?>
          <option value="<?= $e['EmpID'] ?>"><?= $e['FirstName'].' '.$e['LastName'] ?></option>
        <?php endforeach; ?>
      </select>
      <select name="LtyID">
        <?php foreach($leaves as $l): ?>
          <option value="<?= $l['LtyID'] ?>"><?= $l['LType'] ?></option>
        <?php endforeach; ?>
      </select>
      <input type="date" name="StartDate" required>
      <input type="date" name="EndDate" required>
      <input type="number" name="AmountDay" step="1" placeholder="Days">
      <textarea name="Description" placeholder="Reason"></textarea>
      <input name="Status" placeholder="Status (Approved/Pending)">
      <button type="submit">Save</button>
      <button type="button" id="closeModal" style="background:#ccc;color:#000;margin-left:10px;">Cancel</button>
    </form>
  </div>
</div>
</body>
</html>
