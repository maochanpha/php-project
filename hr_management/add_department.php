<?php include 'db.php'; session_start(); if (!isset($_SESSION['user'])) header('Location: login.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['DeptName'];
  $loc = $_POST['Location'];
  $sql = "INSERT INTO Department (DeptName, Location) VALUES ('$name','$loc')";
  $conn->query($sql);
  header('Location: departments.php');
}
?>
<!DOCTYPE html>
<html>
  <head>
<style>
  body {
  margin: 0;
  font-family: 'Segoe UI', sans-serif;
  background: #f1f5f9;
  display: flex;
  min-height: 100vh;
}

.main {
  flex: 1;
  margin-left: 220px;
  padding: 40px;
  background: #f8fafc;
}

.content {
  background: #ffffff;
  padding: 30px 40px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
  max-width: 600px;
}

.content h3 {
  font-size: 1.6rem;
  color: #1e293b;
  margin-bottom: 25px;
}

form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

label {
  color: #475569;
  font-weight: 500;
}

input[type="text"] {
  padding: 10px 12px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 1rem;
  outline: none;
  transition: border 0.3s ease;
}

input[type="text"]:focus {
  border-color: #2563eb;
}

button {
  background: #2563eb;
  color: #ffffff;
  border: none;
  border-radius: 8px;
  padding: 10px 15px;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.2s ease;
  width: fit-content;
}

button:hover {
  background: #1d4ed8;
  transform: translateY(-2px);
}

@media (max-width: 768px) {
  .main {
    margin-left: 0;
    padding: 20px;
  }

  .content {
    padding: 25px;
  }
}

</style>
</head>
<body>
<?php include 'includes/sidebar.php'; ?><div class="main"><?php include 'includes/header.php'; ?>
<section class="content">
  <h3>Add Department</h3>
  <form method="POST">
    <label>Name:</label><input type="text" name="DeptName" required>
    <label>Location:</label><input type="text" name="Location" required>
    <button type="submit">Save</button>
  </form>
</section></div></body></html>
