<?php
session_start();
require '../config/db.php';
if (!isset($_SESSION['user'])) { header("Location: ../auth/login.php"); exit; }

$employees = $pdo->query("
  SELECT e.EmpID, e.FirstName, e.LastName, e.Gender, e.Email, e.Salary, d.DeptName, p.PstName
  FROM Employee e
  LEFT JOIN Department d ON e.DepartmentID = d.DepartmentID
  LEFT JOIN PositionTbl p ON e.PstID = p.PstID
  ORDER BY e.EmpID DESC
")->fetchAll(PDO::FETCH_ASSOC);

$departments = $pdo->query("SELECT * FROM Department")->fetchAll();
$positions = $pdo->query("SELECT * FROM PositionTbl")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Employees</title>
<link rel="stylesheet" href="../public/css/style.css">
<script src="../public/js/modal.js" defer></script>
</head>
<body>
<div class="layout">

  <!-- Sidebar -->
  <aside class="sidebar">
    <h2 class="logo">HR<span>System</span></h2>
    <ul>
      <li><a href="../index.php">🏠 Dashboard</a></li>
      <li><a href="view_employee.php" class="active">👥 Employees</a></li>
      <li><a href="department.php">🏢 Departments</a></li>
      <li><a href="position.php">💼 Positions</a></li>
    </ul>
    <button class="logout-btn" onclick="window.location.href='../auth/logout.php'">Logout</button>
  </aside>

  <!-- Content -->
  <main class="content">
    <div class="topbar">
      <h1>Employee List</h1>
      <button id="themeToggle">🌙</button>
    </div>

    <a href="#" id="openModal" class="add-btn">+ Add Employee</a>

    <div class="table-box">
      <table>
        <tr>
          <th>ID</th><th>Name</th><th>Gender</th><th>Department</th><th>Position</th><th>Email</th><th>Salary</th><th>Action</th>
        </tr>
        <?php foreach($employees as $emp): ?>
        <tr>
          <td><?= $emp['EmpID'] ?></td>
          <td><?= $emp['FirstName'].' '.$emp['LastName'] ?></td>
          <td><?= $emp['Gender'] ?></td>
          <td><?= $emp['DeptName'] ?? '—' ?></td>
          <td><?= $emp['PstName'] ?? '—' ?></td>
          <td><?= $emp['Email'] ?></td>
          <td>$<?= number_format($emp['Salary'], 2) ?></td>
          <td class="actions">
            <a href="edit_employee.php?id=<?= $emp['EmpID'] ?>" class="edit">Edit</a>
            <a href="delete_employee.php?id=<?= $emp['EmpID'] ?>" class="delete" onclick="return confirm('Delete this employee?')">Delete</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </main>
</div>

<!-- 🪄 Modal Add Employee -->
<div class="modal" id="modal">
  <div class="modal-content">
    <h3>Add Employee</h3>
    <form method="post" action="add_employee.php">
      <input name="FirstName" placeholder="First Name" required>
      <input name="LastName" placeholder="Last Name" required>
      <select name="Gender">
        <option value="M">Male</option><option value="F">Female</option><option value="Other">Other</option>
      </select>
      <input name="Email" type="email" placeholder="Email">
      <input name="HPhone" placeholder="Phone">
      <select name="DepartmentID">
        <option value="">Select Department</option>
        <?php foreach($departments as $d): ?>
          <option value="<?= $d['DepartmentID'] ?>"><?= $d['DeptName'] ?></option>
        <?php endforeach; ?>
      </select>
      <select name="PstID">
        <option value="">Select Position</option>
        <?php foreach($positions as $p): ?>
          <option value="<?= $p['PstID'] ?>"><?= $p['PstName'] ?></option>
        <?php endforeach; ?>
      </select>
      <input name="Salary" type="number" step="0.01" placeholder="Salary">
      <button type="submit">Save</button>
      <button type="button" id="closeModal" style="background:#ccc; color:#000; margin-left:10px;">Cancel</button>
    </form>
  </div>
</div>
</body>
</html>
