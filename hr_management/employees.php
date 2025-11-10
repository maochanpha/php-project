<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employees</title>
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
  margin-bottom: 20px;
}

.btn {
  display: inline-block;
  text-decoration: none;
  padding: 10px 16px;
  border-radius: 8px;
  font-size: 0.95rem;
  transition: 0.3s ease;
  margin-bottom: 20px;
}

.btn-save {
  background: #3b82f6;
  color: #fff;
}

.btn-save:hover {
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

td a {
  color: #2563eb;
  text-decoration: none;
  font-weight: 500;
}

td a:hover {
  text-decoration: underline;
}

@media (max-width: 768px) {
  .main {
    margin-left: 0;
    padding: 20px;
  }

  table {
    font-size: 0.85rem;
  }

  th, td {
    padding: 10px 12px;
  }
}

  </style>

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
