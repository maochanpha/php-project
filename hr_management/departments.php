<?php
session_start();
include 'db.php';

// ✅ Require login first
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Departments</title>
  <link rel="stylesheet" href="style.css">
  <style>
    table {
      border-collapse: collapse;
      width: 95%;
      margin: 20px auto;
      background: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 10px 12px;
      text-align: center;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #2980b9;
      color: white;
    }
    tr:hover {
      background-color: #f5f5f5;
    }
    .btn {
      background-color: #3498db;
      color: white;
      padding: 8px 12px;
      border-radius: 5px;
      text-decoration: none;
      display: inline-block;
      margin: 10px 0;
    }
    .message {
      width: 95%;
      margin: 10px auto;
      padding: 10px;
      text-align: center;
      border-radius: 5px;
    }
    .success {
      background: #d4edda;
      color: #155724;
    }
    .error {
      background: #f8d7da;
      color: #721c24;
    }
  </style>
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="main">
  <?php include 'includes/header.php'; ?>

  <section class="content">
    <h3>Departments</h3>

    <!-- ✅ Feedback Messages -->
    <?php if (isset($_GET['msg'])): ?>
      <?php if ($_GET['msg'] == 'added'): ?>
        <div class="message success">✅ Department added successfully!</div>
      <?php elseif ($_GET['msg'] == 'updated'): ?>
        <div class="message success">✏️ Department updated successfully!</div>
      <?php elseif ($_GET['msg'] == 'deleted'): ?>
        <div class="message success">🗑️ Department deleted successfully!</div>
      <?php elseif ($_GET['msg'] == 'error'): ?>
        <div class="message error">❌ Something went wrong. Try again.</div>
      <?php endif; ?>
    <?php endif; ?>

    <a href="add_department.php" class="btn">➕ Add Department</a>

    <table>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Location</th>
        <th>Actions</th>
      </tr>

      <?php
      $result = $conn->query("SELECT DepartmentID, DeptName, Location FROM Department ORDER BY DepartmentID ASC");
      if ($result && $result->num_rows > 0) {
        while ($d = $result->fetch_assoc()) {
          echo "<tr>
                  <td>{$d['DepartmentID']}</td>
                  <td>" . htmlspecialchars($d['DeptName']) . "</td>
                  <td>" . htmlspecialchars($d['Location']) . "</td>
                  <td>
                    <a href='edit_department.php?id={$d['DepartmentID']}'>✏️ Edit</a> |
                    <a href='delete_department.php?id={$d['DepartmentID']}' onclick='return confirm(\"Are you sure you want to delete this department?\")'>🗑️ Delete</a>
                  </td>
                </tr>";
        }
      } else {
        echo "<tr><td colspan='4'>No departments found</td></tr>";
      }
      ?>
    </table>
  </section>
</div>
</body>
</html>
