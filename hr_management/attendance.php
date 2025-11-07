<?php include 'db.php'; session_start(); if (!isset($_SESSION['user'])) header('Location: login.php'); ?>
<!DOCTYPE html>
<html><head><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'includes/sidebar.php'; ?><div class="main"><?php include 'includes/header.php'; ?>
<section class="content">
  <h3>Attendance</h3>
  <form method="POST">
    <label>Employee:</label>
    <select name="EmpID">
      <?php
      $emps = $conn->query("SELECT * FROM Employee");
      while ($e = $emps->fetch_assoc()) echo "<option value='{$e['EmpID']}'>{$e['FirstName']} {$e['LastName']}</option>";
      ?>
    </select>
    <label>Date:</label><input type="date" name="Date" required>
    <label>Check In:</label><input type="time" name="CheckIn" required>
    <label>Check Out:</label><input type="time" name="CheckOut" required>
    <button type="submit">Save</button>
  </form>

  <?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "INSERT INTO Attendance (EmpID, Date, CheckIn, CheckOut)
            VALUES ({$_POST['EmpID']}, '{$_POST['Date']}', '{$_POST['CheckIn']}', '{$_POST['CheckOut']}')";
    $conn->query($sql);
  }
  ?>
  <table>
    <tr><th>Date</th><th>Employee</th><th>Check In</th><th>Check Out</th></tr>
    <?php
    $rows = $conn->query("SELECT A.*, E.FirstName, E.LastName 
                          FROM Attendance A JOIN Employee E ON A.EmpID = E.EmpID ORDER BY Date DESC");
    while ($a = $rows->fetch_assoc()) {
      echo "<tr><td>{$a['Date']}</td><td>{$a['FirstName']} {$a['LastName']}</td>
            <td>{$a['CheckIn']}</td><td>{$a['CheckOut']}</td></tr>";
    }
    ?>
  </table>
</section></div></body></html>
