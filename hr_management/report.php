<?php include 'db.php'; session_start(); if (!isset($_SESSION['user'])) header('Location: login.php'); ?>
<!DOCTYPE html>
<html>
<head>
  <title>HR Reports</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
  margin-left: 220px;
  padding: 30px 40px;
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

.content h4 {
  color: #334155;
  font-size: 1.3rem;
  margin: 30px 0 15px;
}

.dashboard {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
  margin-bottom: 40px;
}

.card {
  background: #ffffff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
  font-size: 1.1rem;
  color: #1e293b;
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card b {
  color: #2563eb;
  font-size: 1.2rem;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
}

canvas {
  background: #ffffff;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

@media (max-width: 768px) {
  .main {
    margin-left: 0;
    padding: 20px;
  }

  .dashboard {
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
    <h3>HR Dashboard</h3>

    <?php
    $empCount = $conn->query("SELECT COUNT(*) AS total FROM Employee")->fetch_assoc()['total'];
    $deptCount = $conn->query("SELECT COUNT(*) AS total FROM Department")->fetch_assoc()['total'];
    $avgSalary = $conn->query("SELECT AVG(Salary) AS avgSal FROM Employee")->fetch_assoc()['avgSal'];
    ?>

    <div class="dashboard">
      <div class="card">Total Employees: <b><?= $empCount ?></b></div>
      <div class="card">Departments: <b><?= $deptCount ?></b></div>
      <div class="card">Average Salary: <b>$<?= number_format($avgSalary,2) ?></b></div>
    </div>

    <h4>Employee per Department</h4>
    <canvas id="empChart" height="100"></canvas>
    <?php
    $data = $conn->query("SELECT D.DeptName, COUNT(E.EmpID) AS total
                          FROM Department D LEFT JOIN Employee E ON D.DepartmentID = E.DNum
                          GROUP BY D.DeptName");
    $labels = [];
    $values = [];
    while ($r = $data->fetch_assoc()) {
      $labels[] = $r['DeptName'];
      $values[] = $r['total'];
    }
    ?>

    <script>
    const ctx = document.getElementById('empChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
          label: 'Employees per Department',
          data: <?= json_encode($values) ?>,
          borderWidth: 1
        }]
      },
      options: {
        scales: { y: { beginAtZero: true } }
      }
    });
    </script>

  </section>
</div>
</body>
</html>
