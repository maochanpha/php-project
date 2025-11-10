<?php include 'db.php';
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - HR Management</title>
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
  text-align: center;
}

.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 25px;
  margin-top: 30px;
}

.card {
  background: #ffffff;
  border-radius: 16px;
  padding: 30px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
  text-align: center;
  transition: 0.3s ease;
}

.card:hover {
  transform: translateY(-6px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.card h4 {
  font-size: 1.2rem;
  color: #334155;
  margin-bottom: 10px;
}

.card p {
  font-size: 2.2rem;
  font-weight: bold;
  color: #2563eb;
  margin: 0;
}

@media (max-width: 768px) {
  .main {
    margin-left: 0;
    padding: 20px;
  }
  .cards {
    grid-template-columns: 1fr;
  }
}

  </style>
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
