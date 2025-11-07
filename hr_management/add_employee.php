<?php include 'db.php'; session_start(); if (!isset($_SESSION['user'])) header('Location: login.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $fname = $_POST['FirstName'];
  $lname = $_POST['LastName'];
  $gender = $_POST['Gender'];
  $salary = $_POST['Salary'];
  
  // Handle file upload
  $photo = '';
  if (!empty($_FILES['photo']['name'])) {
    $photo = 'uploads/' . basename($_FILES['photo']['name']);
    move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
  }

  $sql = "INSERT INTO Employee (FirstName, LastName, Gender, Salary, Photo)
          VALUES ('$fname', '$lname', '$gender', '$salary', '$photo')";
  $conn->query($sql);
  header('Location: employees.php');
}
?>
<!DOCTYPE html>
<html><head><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'includes/sidebar.php'; ?><div class="main"><?php include 'includes/header.php'; ?>
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

  <label>Date of Birth:</label>
  <input type="date" name="DOB">

  <label>Nationality:</label>
  <input type="text" name="Nationality" placeholder="e.g., Cambodian">

  <label>Marital Status:</label>
  <select name="MaritalStatus">
    <option value="">-- Select --</option>
    <option>Single</option>
    <option>Married</option>
    <option>Divorced</option>
    <option>Widowed</option>
  </select>

  <h3>Contact Information</h3>

  <label>Email:</label>
  <input type="email" name="Email" placeholder="example@gmail.com">

  <label>Phone:</label>
  <input type="text" name="Phone" placeholder="+855...">

  <label>Address:</label>
  <textarea name="Address" rows="2" placeholder="House No, Street, City"></textarea>

  <h3>Job Details</h3>

  <label>Department:</label>
  <select name="DNum" required>
    <option value="">-- Select Department --</option>
    <?php
      $depResult = $conn->query("SELECT * FROM Department");
      while ($dep = $depResult->fetch_assoc()) {
        echo "<option value='{$dep['DepartmentID']}'>{$dep['DeptName']}</option>";
      }
    ?>
  </select>

  <label>Position:</label>
  <select name="PstID" required>
    <option value="">-- Select Position --</option>
    <?php
      $posResult = $conn->query("SELECT * FROM Position");
      while ($pos = $posResult->fetch_assoc()) {
        echo "<option value='{$pos['PostID']}'>{$pos['PostName']}</option>";
      }
    ?>
  </select>

  <label>Salary ($):</label>
  <input type="number" name="Salary" min="0" required>

  <label>Date Applied:</label>
  <input type="date" name="DateApplied">

  <h3>Profile Photo</h3>
  <label>Upload Photo:</label>
  <input type="file" name="photo">

  <button type="submit">Save Employee</button>
</form>

</section></div></body></html>
