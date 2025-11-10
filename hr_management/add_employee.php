<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user'])) header('Location: login.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $fname = $_POST['FirstName'];
  $lname = $_POST['LastName'];
  $gender = $_POST['Gender'];
  $salary = $_POST['Salary'];
  $pst = $_POST['PstID'];

  $photo = '';
  if (!empty($_FILES['photo']['name'])) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $photo = $uploadDir . basename($_FILES['photo']['name']);
    move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
  }

  $sql = "INSERT INTO Employee (FirstName, LastName, Gender, Salary, PstID, Photo)
          VALUES ('$fname', '$lname', '$gender', '$salary', '$pst', '$photo')";
  $conn->query($sql);
  header('Location: employees.php');
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <style>
body {
  margin: 0;
  font-family: "Poppins", sans-serif;
  background: #eef2f7;
  color: #1e293b;
  display: flex;
}

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

.main {
  margin-left: 230px;
  padding: 40px;
  width: calc(100% - 230px);
}

.header {
  background: #fff;
  border-bottom: 2px solid #e5e7eb;
  padding: 15px 25px;
  border-radius: 10px;
  margin-bottom: 25px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header h1 {
  font-size: 22px;
  color: #1e40af;
  margin: 0;
}

.content {
  background: #fff;
  border-radius: 12px;
  padding: 30px;
  box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
}

.content h3 {
  color: #1e3a8a;
  border-left: 5px solid #2563eb;
  padding-left: 12px;
  margin-bottom: 25px;
  font-size: 22px;
}

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

label {
  font-weight: 500;
  color: #334155;
  margin-top: 5px;
}

input[type="text"],
input[type="number"],
input[type="file"],
select {
  padding: 10px 12px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 15px;
  transition: 0.2s;
}

input:focus,
select:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
}

button {
  margin-top: 15px;
  background: #2563eb;
  color: white;
  border: none;
  border-radius: 8px;
  padding: 10px 16px;
  font-size: 15px;
  cursor: pointer;
  transition: 0.3s;
  width: fit-content;
}

button:hover {
  background: #1e40af;
  transform: translateY(-1px);
}

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

    <label>Position:</label>
    <select name="PstID" required>
      <option value="">-- Select Position --</option>
      <?php
      $pos = $conn->query("SELECT * FROM Position ORDER BY PostName ASC");
      while ($p = $pos->fetch_assoc()) {
        echo "<option value='{$p['PstID']}'>{$p['PostName']}</option>";
      }
      ?>
    </select>

    <h3>Profile Photo</h3>
    <label>Upload Photo:</label>
    <input type="file" name="photo">

    <button type="submit">Save Employee</button>
  </form>
</section>
</div>
</body>
</html>
