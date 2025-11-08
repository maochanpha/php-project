<?php
ob_start(); // ✅ Start output buffering right away
include 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Employee ID is missing in URL.");
}

$id = intval($_GET['id']);

// ✅ Fetch Employee Info
$empQuery = $conn->prepare("SELECT * FROM Employee WHERE EmpID = ?");
$empQuery->bind_param("i", $id);
$empQuery->execute();
$emp = $empQuery->get_result()->fetch_assoc();

if (!$emp) {
    die("❌ Employee not found.");
}

// ✅ Fetch Dependents
$deps = $conn->prepare("SELECT * FROM dependents WHERE EmpID = ?");
$deps->bind_param("i", $id);
$deps->execute();
$depsResult = $deps->get_result();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Employee Profile</title>
  <style>
    /* === General Page Layout === */
body {
  margin: 0;
  font-family: "Segoe UI", sans-serif;
  background: #f5f6fa;
  color: #2c3e50;
}

.sidebar {
  width: 230px;
  background: #1e293b; /* deep blue */
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  display: flex;
  flex-direction: column;
  padding: 20px 0;
  color: white;
}

.sidebar h2 {
  text-align: center;
  margin-bottom: 20px;
  font-size: 20px;
  letter-spacing: 1px;
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
  transition: 0.3s;
  font-size: 15px;
}

.sidebar ul li a:hover,
.sidebar ul li a.active {
  background: #2563eb;
  border-left: 4px solid #93c5fd;
}

.main {
  margin-left: 230px; /* leave room for sidebar */
  padding: 20px;
}

section.content {
  background: #fff;
  border-radius: 12px;
  padding: 25px 30px;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
}

/* === Headings === */
h3, h4 {
  color: #1e3a8a;
  border-left: 4px solid #1e90ff;
  padding-left: 10px;
  margin-bottom: 15px;
}

/* === Employee Profile Card === */
.profile-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  background: #ffffff;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  padding: 30px 20px;
  width: 280px;
  margin-bottom: 30px;
}

.profile-card img {
  width: 140px;
  height: 140px;
  border-radius: 50%;
  border: 4px solid #3498db;
  margin-bottom: 15px;
}

.profile-card h2 {
  margin: 10px 0 5px;
  color: #2c3e50;
}

.profile-card p {
  margin: 4px 0;
  color: #555;
  font-size: 15px;
}

/* === Dependents Table === */
table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 30px;
}

table th, table td {
  padding: 10px 15px;
  border-bottom: 1px solid #e5e7eb;
  text-align: left;
  font-size: 15px;
}

table th {
  background: #e8f0fe;
  color: #1e3a8a;
  font-weight: 600;
}

table tr:hover {
  background: #f9fafb;
}

/* === Buttons === */
button, .btn {
  background: #1e90ff;
  border: none;
  color: white;
  padding: 8px 14px;
  border-radius: 6px;
  font-size: 14px;
  cursor: pointer;
  transition: 0.3s;
}

button:hover, .btn:hover {
  background: #0d6efd;
}

/* === Form Styling === */
.add-dependent input,
.add-dependent select,
form input,
form select {
  padding: 8px 10px;
  margin: 5px 8px 10px 0;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 14px;
  width: 160px;
}

.add-dependent button {
  margin-left: 5px;
}

/* === Action Icons === */
a {
  text-decoration: none;
  font-size: 18px;
  margin-right: 8px;
}

a:hover {
  opacity: 0.7;
}

/* === Responsive === */
@media (max-width: 768px) {
  .main {
    margin-left: 0;
    padding: 15px;
  }

  .profile-card {
    width: 100%;
  }

  table, form {
    font-size: 13px;
  }

  input, select, button {
    width: 100%;
    margin-bottom: 10px;
  }
}

  </style>
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="main">
<?php include 'includes/header.php'; ?>
<section class="content">
  <h3>Employee Profile</h3>

  <div class="profile-card">
    <img src="<?= htmlspecialchars($emp['Photo']) ?>" width="100" height="100" style="border-radius:50%">
    <h2><?= htmlspecialchars($emp['FirstName']) ?> <?= htmlspecialchars($emp['LastName']) ?></h2>
    <p>Gender: <?= htmlspecialchars($emp['Gender']) ?></p>
    <p>Salary: $<?= htmlspecialchars($emp['Salary']) ?></p>
  </div>

  <h4>Dependents</h4>
  <table>
    <tr>
      <th>Name</th>
      <th>Relationship</th>
      <th>Gender</th>
      <th>Phone</th>
      <th>Occupation</th>
      <th>DOB</th>
      <th>Action</th>
    </tr>
    <?php while($d = $depsResult->fetch_assoc()) { ?>
      <tr>
        <td><?= htmlspecialchars($d['DepName']) ?></td>
        <td><?= htmlspecialchars($d['Relationship']) ?></td>
        <td><?= htmlspecialchars($d['Gender']) ?></td>
        <td><?= htmlspecialchars($d['Phone']) ?></td>
        <td><?= htmlspecialchars($d['Occupation']) ?></td>
        <td><?= htmlspecialchars($d['DOB']) ?></td>
        <td>
          <a href="?id=<?= $id ?>&editDep=<?= $d['DepID'] ?>">✏️</a>
          <a href="?id=<?= $id ?>&delDep=<?= $d['DepID'] ?>" onclick="return confirm('Delete dependent?')">❌</a>
        </td>
      </tr>
    <?php } ?>
  </table>

<?php
// ✅ DELETE Dependent
if (isset($_GET['delDep'])) {
    $delDep = intval($_GET['delDep']);
    $del = $conn->prepare("DELETE FROM dependents WHERE DepID=?");
    $del->bind_param("i", $delDep);
    $del->execute();
    header("Location: employee_profile.php?id=$id");
    exit;
}

// ✅ EDIT Dependent
if (isset($_GET['editDep'])) {
    $editDep = intval($_GET['editDep']);
    $dep = $conn->query("SELECT * FROM dependents WHERE DepID=$editDep")->fetch_assoc();
?>
  <h4>Edit Dependent</h4>
  <form method="POST">
    <input type="text" name="DepName" value="<?= htmlspecialchars($dep['DepName']) ?>" required>
    <input type="text" name="Relationship" value="<?= htmlspecialchars($dep['Relationship']) ?>" required>
    <select name="Gender" required>
      <option value="">Select Gender</option>
      <option value="Male" <?= $dep['Gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
      <option value="Female" <?= $dep['Gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
    </select>
    <input type="text" name="Phone" value="<?= htmlspecialchars($dep['Phone']) ?>" placeholder="Phone">
    <input type="text" name="Occupation" value="<?= htmlspecialchars($dep['Occupation']) ?>" placeholder="Occupation">
    <input type="date" name="DOB" value="<?= htmlspecialchars($dep['DOB']) ?>" required>
    <button type="submit" name="updateDep">Update</button>
  </form>
<?php
}
if (isset($_POST['updateDep'])) {
    $update = $conn->prepare("UPDATE dependents 
                              SET DepName=?, Relationship=?, Gender=?, Phone=?, Occupation=?, DOB=? 
                              WHERE DepID=?");
    $update->bind_param("ssssssi", $_POST['DepName'], $_POST['Relationship'], $_POST['Gender'],
        $_POST['Phone'], $_POST['Occupation'], $_POST['DOB'], $_GET['editDep']);
    $update->execute();
    header("Location: employee_profile.php?id=$id");
    exit;
}
?>

  <h4>Add Dependent</h4>
  <form method="POST" class="add-dependent">
    <input type="text" name="DepName" placeholder="Name" required>
    <input type="text" name="Relationship" placeholder="Relationship" required>
    <select name="Gender" required>
      <option value="">Select Gender</option>
      <option value="Male">Male</option>
      <option value="Female">Female</option>
    </select>
    <input type="text" name="Phone" placeholder="Phone">
    <input type="text" name="Occupation" placeholder="Occupation">
    <input type="date" name="DOB" required>
    <button type="submit" name="addDep">Add</button>
  </form>

<?php
// ✅ ADD Dependent
if (isset($_POST['addDep'])) {
    $add = $conn->prepare("INSERT INTO dependents (EmpID, DepName, Relationship, Gender, Phone, Occupation, DOB)
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $add->bind_param("issssss", $id, $_POST['DepName'], $_POST['Relationship'], 
                     $_POST['Gender'], $_POST['Phone'], $_POST['Occupation'], $_POST['DOB']);
    $add->execute();
    header("Location: employee_profile.php?id=$id");
    exit;
}
?>

</section>
</div>
</body>
</html>
<?php ob_end_flush(); // ✅ End output buffering ?>
