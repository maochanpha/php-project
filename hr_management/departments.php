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
  background: #3b82f6;
  color: #fff;
}

.btn:hover {
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

.message {
  padding: 12px 18px;
  border-radius: 8px;
  margin-bottom: 20px;
  font-size: 0.95rem;
  display: inline-block;
}

.message.success {
  background: #dcfce7;
  color: #166534;
  border: 1px solid #86efac;
}

.message.error {
  background: #fee2e2;
  color: #991b1b;
  border: 1px solid #fca5a5;
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
