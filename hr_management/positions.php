<?php include 'db.php'; session_start(); if (!isset($_SESSION['user'])) header('Location: login.php'); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Position Management</title>
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

form {
  background: #ffffff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
  align-items: center;
  margin-bottom: 25px;
}

form input[type="text"] {
  flex: 1;
  min-width: 220px;
  padding: 10px 14px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 1rem;
  transition: 0.3s ease;
}

form input:focus {
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

td a {
  color: #dc2626;
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

  form {
    flex-direction: column;
    align-items: stretch;
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
<?php include 'includes/sidebar.php'; ?>
<div class="main">
  <?php include 'includes/header.php'; ?>
  <section class="content">
    <h3>Manage Positions</h3>

    <form method="POST">
      <input type="text" name="PostName" placeholder="Position Name" required>
      <input type="text" name="Description" placeholder="Description">
      <button type="submit" name="add">Add Position</button>
    </form>

    <?php
    if (isset($_POST['add'])) {
      $sql = "INSERT INTO Position (PostName, Description)
              VALUES ('{$_POST['PostName']}', '{$_POST['Description']}')";
      $conn->query($sql);
    }

    if (isset($_GET['delete'])) {
      $id = $_GET['delete'];
      $conn->query("DELETE FROM Position WHERE PstID=$id");
      header("Location: positions.php");
    }

    $positions = $conn->query("SELECT * FROM Position");
    ?>

    <table>
      <tr><th>ID</th><th>Position</th><th>Description</th><th>Action</th></tr>
      <?php while($p = $positions->fetch_assoc()) { ?>
        <tr>
          <td><?= $p['PstID'] ?></td>
          <td><?= $p['PostName'] ?></td>
          <td><?= $p['Description'] ?></td>
          <td>
            <a href="?delete=<?= $p['PstID'] ?>" onclick="return confirm('Delete this?')">❌</a>
          </td>
        </tr>
      <?php } ?>
    </table>
  </section>
</div>
</body>
</html>
