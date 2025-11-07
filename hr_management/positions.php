<?php include 'db.php'; session_start(); if (!isset($_SESSION['user'])) header('Location: login.php'); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Position Management</title>
  <link rel="stylesheet" href="style.css">
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
