<?php
include 'db.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM employee WHERE EmpID=$id");
$row = $result->fetch_assoc();

// Load position list
$posResult = $conn->query("SELECT * FROM position");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Employee</title>
  <style>
    /* General Body */
body {
  background-color: #f4f7fc;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  margin: 0;
  padding: 0;
}

/* Topbar */
.topbar {
  background-color: #2196F3;
  color: white;
  font-size: 22px;
  padding: 15px 25px;
  font-weight: bold;
}

/* Form Container */
.form-container {
  background: white;
  width: 700px;
  margin: 50px auto;
  padding: 30px 40px;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.form-container h2 {
  text-align: center;
  color: #333;
  margin-bottom: 25px;
}

/* Grid Layout */
.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
}

/* Input Group */
.input-group {
  display: flex;
  flex-direction: column;
}

.input-group label {
  font-weight: 600;
  color: #333;
  margin-bottom: 6px;
}

.input-group input,
.input-group select,
.input-group textarea {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 15px;
  transition: 0.2s;
}

.input-group input:focus,
.input-group select:focus,
.input-group textarea:focus {
  border-color: #2196F3;
  outline: none;
  box-shadow: 0 0 3px #90caf9;
}

/* Textarea full width */
textarea {
  resize: none;
  height: 80px;
}

/* Buttons */
.form-actions {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-top: 30px;
}

button {
  background-color: #2196F3;
  color: white;
  border: none;
  padding: 12px 25px;
  font-size: 16px;
  font-weight: 600;
  border-radius: 6px;
  cursor: pointer;
  transition: 0.2s;
}

button:hover {
  background-color: #1976D2;
}

.btn-cancel {
  background-color: #ccc;
  color: #333;
  padding: 12px 25px;
  border-radius: 6px;
  font-weight: 600;
}

.btn-cancel:hover {
  background-color: #b3b3b3;
}

/* Alerts */
.alert-success {
  background-color: #d4edda;
  color: #155724;
  padding: 12px;
  border-radius: 6px;
  margin-bottom: 15px;
  border: 1px solid #c3e6cb;
  text-align: center;
}

.alert-error {
  background-color: #f8d7da;
  color: #721c24;
  padding: 12px;
  border-radius: 6px;
  margin-bottom: 15px;
  border: 1px solid #f5c6cb;
  text-align: center;
}

  </style>
</head>
<body>
  <div class="topbar">Edit Employee</div>
  <div class="form-container">
    <h2>Edit Employee</h2>
    <form method="POST" action="update_employee.php">
      <input type="hidden" name="EmpID" value="<?= $row['EmpID'] ?>">

      <div class="form-grid">
        <div class="input-group">
          <label>First Name</label>
          <input type="text" name="FirstName" value="<?= $row['FirstName'] ?>" required>
        </div>
        <div class="input-group">
          <label>Last Name</label>
          <input type="text" name="LastName" value="<?= $row['LastName'] ?>" required>
        </div>

        <div class="input-group">
          <label>Gender</label>
          <select name="Gender" required>
            <option value="Male" <?= $row['Gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $row['Gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
          </select>
        </div>

        <div class="input-group">
          <label>Date of Birth</label>
          <input type="date" name="DOB" value="<?= $row['DOB'] ?>">
        </div>

 <div class="input-group">
  <label>Position</label>
  <select name="PstID" required>
    <option value="">-- Select Position --</option>
    <?php while ($pos = $posResult->fetch_assoc()) { ?>
      <option value="<?= $pos['PstID'] ?>" <?= $row['PstID'] == $pos['PstID'] ? 'selected' : '' ?>>
        <?= $pos['PostName'] ?>
      </option>
    <?php } ?>
  </select>
</div>


      </div>

      <div class="form-actions">
        <button type="submit">Save Changes</button>
        <a href="employee_list.php" class="btn-cancel">Cancel</a>
      </div>
    </form>
  </div>
</body>
</html>
