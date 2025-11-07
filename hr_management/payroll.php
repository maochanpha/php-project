<?php include 'db.php'; session_start(); if (!isset($_SESSION['user'])) header('Location: login.php'); ?>
<!DOCTYPE html>
<html><head><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'includes/sidebar.php'; ?><div class="main"><?php include 'includes/header.php'; ?>
<section class="content">
  <h3>Payroll Management</h3>
  <form method="POST">
    <label>Employee:</label>
    <select name="EmpID">
      <?php
      $emps = $conn->query("SELECT EmpID, FirstName, LastName FROM Employee");
      while ($e = $emps->fetch_assoc()) {
        echo "<option value='{$e['EmpID']}'>{$e['FirstName']} {$e['LastName']}</option>";
      }
      ?>
    </select>
    <label>Base Salary:</label><input type="number" name="BaseSalary" required>
    <label>Allowance:</label><input type="number" name="Allowance" required>
    <label>Deduction:</label><input type="number" name="Deduction" required>
    <label>Pay Date:</label><input type="date" name="PayDate" required>
    <button type="submit">Save Payroll</button>
  </form>

  <?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $net = $_POST['BaseSalary'] + $_POST['Allowance'] - $_POST['Deduction'];
    $sql = "INSERT INTO Payroll (EmpID, BaseSalary, Allowance, Deduction, NetSalary, PayDate)
            VALUES ({$_POST['EmpID']}, {$_POST['BaseSalary']}, {$_POST['Allowance']}, {$_POST['Deduction']}, $net, '{$_POST['PayDate']}')";
    $conn->query($sql);
  }
  ?>

  <h4>Payroll Records</h4>
  <table>
    <tr><th>ID</th><th>Employee</th><th>Base</th><th>Allowance</th><th>Deduction</th><th>Net</th><th>Date</th></tr>
    <?php
    $rows = $conn->query("SELECT P.*, E.FirstName, E.LastName 
                          FROM Payroll P JOIN Employee E ON P.EmpID = E.EmpID ORDER BY PayDate DESC");
    while ($p = $rows->fetch_assoc()) {
      echo "<tr>
        <td>{$p['PayrollID']}</td>
        <td>{$p['FirstName']} {$p['LastName']}</td>
        <td>{$p['BaseSalary']}</td>
        <td>{$p['Allowance']}</td>
        <td>{$p['Deduction']}</td>
        <td><b>{$p['NetSalary']}</b></td>
        <td>{$p['PayDate']}</td>
      </tr>";
    }
    ?>
  </table>
</section></div></body></html>
