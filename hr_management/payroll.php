<?php include 'db.php'; session_start(); if (!isset($_SESSION['user'])) header('Location: login.php'); ?>
<!DOCTYPE html>
<html><head>
  <style>
    body {
  margin: 0;
  font-family: 'Segoe UI', Arial, sans-serif;
  background: #f1f5f9;
  display: flex;
  min-height: 100vh;
}

.main {
  flex: 1;
  padding: 30px 40px;
  margin-left: 220px;
  background: #f8fafc;
}

.content {
  margin-top: 20px;
}

.content h3 {
  color: #1e293b;
  font-size: 1.8rem;
  margin-bottom: 25px;
}

.content h4 {
  color: #334155;
  font-size: 1.3rem;
  margin: 30px 0 15px;
}

form {
  background: #ffffff;
  padding: 15px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  display: grid;
  gap: 15px 20px;
  margin-bottom: 30px;
  align-items: end;
}

form label {
  font-weight: 500;
  color: #334155;
  display: block;
  margin-bottom: 6px;
  
}

form input, 
form select {
  width: 80%;
  padding: 10px 14px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 1rem;
  transition: 0.3s ease;
}

form input:focus, 
form select:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 5px rgba(59,130,246,0.4);
  outline: none;
}

form button {
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 8px;
  padding: 10px 20px;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.3s ease;
}

form button:hover {
  background: #2563eb;
}

table {
  width: 100%;
  border-collapse: collapse;
  background: #ffffff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

th, td {
  padding: 14px 18px;
  text-align: left;
  border-bottom: 1px solid #e2e8f0;
  font-size: 0.95rem;
}

th {
  background: #3b82f6;
  color: #ffffff;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-size: 0.9rem;
}

tr:hover td {
  background: #f1f5f9;
}

td b {
  color: #16a34a;
}

@media (max-width: 768px) {
  .main {
    margin-left: 0;
    padding: 20px;
  }

  form {
    grid-template-columns: 1fr;
  }

  form button {
    width: 100%;
  }

  th, td {
    padding: 10px 12px;
  }
}

  </style>
</head>
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
