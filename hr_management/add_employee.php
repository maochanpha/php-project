<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user'])) header('Location: login.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $fname = $_POST['FirstName'];
  $lname = $_POST['LastName'];
  $gender = $_POST['Gender'];
  $salary = $_POST['Salary'];

  // Handle file upload
  $photo = '';
  if (!empty($_FILES['photo']['name'])) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $photo = $uploadDir . basename($_FILES['photo']['name']);
    move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
  }

  // Insert only existing columns
  $sql = "INSERT INTO Employee (FirstName, LastName, Gender, Salary, Photo)
          VALUES ('$fname', '$lname', '$gender', '$salary', '$photo')";
  $conn->query($sql);
  header('Location: employees.php');
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <style>
    /* === GLOBAL === */
body {
  margin: 0;
  font-family: "Segoe UI", sans-serif;
  background: #f5f6fa;
  color: #2c3e50;
  display: flex;
}

/* === SIDEBAR === */
.sidebar {
  width: 230px;
  background: #1e293b;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  color: #fff;
  display: flex;
  flex-direction: column;
  padding: 20px 0;
}

.sidebar h2 {
  text-align: center;
  font-size: 20px;
  margin-bottom: 25px;
  font-weight: 600;
}

.sidebar ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.sidebar ul li {
  margin: 10px 0;
}

.sidebar ul li a {
  display: block;
  padding: 12px 20px;
  color: white;
  text-decoration: none;
  font-size: 15px;
  transition: 0.3s;
}

.sidebar ul li a:hover,
.sidebar ul li a.active {
  background: #2563eb;
  border-left: 4px solid #93c5fd;
}

/* === MAIN === */
.main {
  margin-left: 230px;
  padding: 30px 40px;
  width: calc(100% - 230px);
}

/* === HEADER === */
.header {
  background: #fff;
  border-bottom: 2px solid #e5e7eb;
  padding: 12px 20px;
  border-radius: 8px;
  margin-bottom: 25px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header h1 {
  font-size: 22px;
  color: #1e3a8a;
  margin: 0;
}

/* === CONTENT === */
.content {
  background: #fff;
  border-radius: 12px;
  padding: 25px 30px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

/* === PAGE TITLE === */
.content h3 {
  color: #1e3a8a;
  border-left: 4px solid #2563eb;
  padding-left: 10px;
  margin-bottom: 25px;
  font-size: 22px;
}

/* === FORM === */
form {
  display: flex;
  flex-direction: column;
  gap: 15px;
  max-width: 600px;
  margin-top: 10px;
}

form h3 {
  font-size: 18px;
  color: #1e3a8a;
  margin-top: 20px;
  border-bottom: 1px solid #e5e7eb;
  padding-bottom: 5px;
}

form label {
  font-weight: 500;
  color: #374151;
  margin-top: 5px;
}

form input[type="text"],
form input[type="number"],
form input[type="file"],
form select {
  padding: 10px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 15px;
  transition: 0.2s;
}

form input:focus,
form select:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
}

/* === BUTTON === */
form button {
  margin-top: 15px;
  background: #2563eb;
  color: white;
  border: none;
  border-radius: 6px;
  padding: 10px 16px;
  font-size: 15px;
  cursor: pointer;
  transition: 0.3s;
  width: fit-content;
}

form button:hover {
  background: #1e40af;
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
  .sidebar {
    display: none;
  }

  .main {
    margin-left: 0;
    width: 100%;
    padding: 20px;
  }

  form {
    width: 100%;
  }
}

  </style>
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="main">
<?php include 'includes/header.php'; ?>
<section class="content">
  <h3>Add Employee</h3>

  <form method="POST" enctype="multipart/form-data">
    <h3>Employee Information</h3>
    <label>First Name:</label>
    <input type="text" name="FirstName" required>

    <label>Last Name:</label>
    <input type="text" name="LastName" required>

    <label>Gender:</label>
    <select name="Gender" required>
      <option value="">-- Select Gender --</option>
      <option>Male</option>
      <option>Female</option>
    </select>

    <label>Salary ($):</label>
    <input type="number" name="Salary" min="0" required>

    <h3>Profile Photo</h3>
    <label>Upload Photo:</label>
    <input type="file" name="photo">

    <button type="submit">Save Employee</button>
  </form>
</section>
</div>
</body>
</html>
