<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employees</title>
  <link rel="stylesheet" href="style.css">

</head>
<body>
  <?php include 'includes/sidebar.php'; ?>
  <div class="main">
    <?php include 'includes/header.php'; ?>
    <section class="content">
      <h3>Employee List</h3>
      <a href="add_employee.php" class="btn btn-save">➕ Add Employee</a>

      <table>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Gender</th>
          <th>Salary ($)</th>
          <th>Position</th>
          <th>Action</th>
        </tr>

        <?php
        // Join Employee with Position table to get PostName
        $sql = "SELECT e.*, p.PostName 
                FROM Employee e
                LEFT JOIN Position p ON e.PstID = p.PstID
                ORDER BY e.EmpID DESC";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            echo '<tr>
                    <td>'.htmlspecialchars($row['EmpID']).'</td>
                    <td>'.htmlspecialchars($row['FirstName'].' '.$row['LastName']).'</td>
                    <td>'.htmlspecialchars($row['Gender']).'</td>
                    <td>'.htmlspecialchars($row['Salary']).'</td>
                    <td>'.htmlspecialchars($row['PostName'] ?? '—').'</td>
                    <td>
                      <a href="employee_profile.php?id='.$row['EmpID'].'">👁 View</a> |
                      <a href="edit_employee.php?id='.$row['EmpID'].'">✏️ Edit</a> |
                      <a href="delete_employee.php?id='.$row['EmpID'].'" onclick="return confirm(\'Delete employee?\')">🗑️ Delete</a>
                    </td>
                  </tr>';
          }
        } else {
          echo '<tr><td colspan="6" style="text-align:center;">No employees found.</td></tr>';
        }
        ?>
      </table>
    </section>
  </div>
</body>
</html>
