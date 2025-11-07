<?php include 'db.php'; session_start(); if (!isset($_SESSION['user'])) header('Location: login.php'); ?>
<!DOCTYPE html>
<html><head><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'includes/sidebar.php'; ?><div class="main"><?php include 'includes/header.php'; ?>
<section class="content">
  <h3>Leave Applications</h3>
  <a href="add_leave.php" class="btn">➕ Apply Leave</a>
  <table>
    <tr><th>ID</th><th>Employee</th><th>Type</th><th>Dates</th><th>Status</th><th>Action</th></tr>
    <?php
    $result = $conn->query("SELECT L.*, E.FirstName, E.LastName 
                            FROM EmpLeave L JOIN Employee E ON L.EmpID = E.EmpID ORDER BY LeaveID DESC");
    while ($l = $result->fetch_assoc()) {
      echo "<tr>
        <td>{$l['LeaveID']}</td>
        <td>{$l['FirstName']} {$l['LastName']}</td>
        <td>{$l['LeaveType']}</td>
        <td>{$l['StartDate']} - {$l['EndDate']}</td>
        <td>{$l['Status']}</td>
        <td>
          <a href='approve_leave.php?id={$l['LeaveID']}&s=Approved'>✅</a> 
          <a href='approve_leave.php?id={$l['LeaveID']}&s=Rejected'>❌</a>
        </td>
      </tr>";
    }
    ?>
  </table>
</section></div></body></html>
