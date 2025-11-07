<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - HR Management</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include 'includes/sidebar.php'; ?>
  <div class="main">
    <?php include 'includes/header.php'; ?>

    <section class="content">
      <h3>Welcome to HR Management Dashboard</h3>
      <div class="cards">
        <?php
          $empCount = $conn->query("SELECT COUNT(*) as c FROM Employee")->fetch_assoc()['c'];
          $depCount = $conn->query("SELECT COUNT(*) as c FROM Department")->fetch_assoc()['c'];
          $posCount = $conn->query("SELECT COUNT(*) as c FROM Position")->fetch_assoc()['c'];
        ?>
        <div class="card">
          <h4>Employees</h4>
          <p><?= $empCount ?></p>
        </div>
        <div class="card">
          <h4>Departments</h4>
          <p><?= $depCount ?></p>
        </div>
        <div class="card">
          <h4>Positions</h4>
          <p><?= $posCount ?></p>
        </div>
      </div>
    </section>

  </div>
</body>
</html>
