<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    .sidebar {
  position: fixed;
  top: 0;
  left: 0;
  width: 220px;
  height: 100vh;
  background: #1e293b;
  color: #f8fafc;
  display: flex;
  flex-direction: column;
  padding-top: 30px;
}

.logo {
  text-align: center;
  margin-bottom: 30px;
}

.logo h2 {
  color: #f8fafc;
  font-size: 1.2rem;
  margin: 0;
  letter-spacing: 1px;
}

.logo h2 span {
  color: #3b82f6;
  margin: 10px;
}

.menu {
  list-style: none;
  padding: 0;
  margin: 0;
  flex: 1;
}

.menu li {
  margin: 10px 0;
}

.menu a {
  display: block;
  padding: 12px 20px;
  color: #e2e8f0;
  text-decoration: none;
  font-size: 1rem;
  transition: all 0.3s ease;
  border-left: 4px solid transparent;
}

.menu a:hover {
  background: #334155;
  border-left: 4px solid #3b82f6;
  color: #ffffff;
}

.menu a.logout {
  color: #fca5a5;
}

.menu a.logout:hover {
  background: #dc2626;
  color: #ffffff;
  border-left: 4px solid #dc2626;
}

@media (max-width: 768px) {
  .sidebar {
    width: 180px;
  }
  .menu a {
    font-size: 0.9rem;
    padding: 10px 15px;
  }
}

  </style>
</head>
<body>
  <aside class="sidebar">
  <div class="logo">
    <h2>HR<span>Management System</span></h2>
  </div>
  <ul class="menu">
    <li><a href="index.php">🏠 Dashboard</a></li>
    <li><a href="employees.php">👨‍💼 Employees</a></li>
    <li><a href="departments.php">🏢 Departments</a></li>
    <li><a href="positions.php">📋 Positions</a></li>
    <li><a href="payroll.php">💰 Payroll</a></li>
    <li><a href="report.php">📊 Reports</a></li>
    <li><a href="logout.php" class="logout">🚪 Logout</a></li>

  </ul>
</aside>

</body>
</html>