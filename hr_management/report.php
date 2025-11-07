<?php include 'db.php'; session_start(); if (!isset($_SESSION['user'])) header('Location: login.php'); ?>
<!DOCTYPE html>
<html>
<head>
  <title>HR Reports</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
