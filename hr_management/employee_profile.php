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
}

.content h3 {
  color: #1e293b;
  font-size: 1.6rem;
  margin-bottom: 25px;
}

h4 {
  color: #334155;
  margin-top: 40px;
  margin-bottom: 15px;
}

/* ===== Profile Card ===== */
.profile-card {
  background: #f9fafb;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 25px;
  max-width: 400px;
  text-align: center;
  margin-bottom: 30px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.profile-card img {
  border: 3px solid #2563eb;
  margin-bottom: 15px;
}

.profile-card h2 {
  margin: 10px 0;
  color: #1e293b;
}

.profile-card p {
  color: #475569;
  font-size: 0.95rem;
}

/* ===== Tables ===== */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
  background: #ffffff;
}

th, td {
  padding: 10px 14px;
  text-align: left;
  border-bottom: 1px solid #e2e8f0;
}

th {
  background: #2563eb;
  color: #ffffff;
  text-transform: uppercase;
  font-size: 0.85rem;
}

tr:hover {
  background: #f1f5f9;
}

/* ===== Forms ===== */
form {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  background: #f9fafb;
  padding: 15px;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  margin-top: 10px;
}

form input, form select {
  flex: 1 1 180px;
  padding: 8px 10px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 0.95rem;
  outline: none;
  transition: border 0.3s ease;
}

form input:focus, form select:focus {
  border-color: #2563eb;
}

button {
  background: #2563eb;
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 10px 15px;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.2s ease;
}

button:hover {
  background: #1d4ed8;
  transform: translateY(-2px);
}

/* ===== Responsive ===== */
@media (max-width: 768px) {
  .main {
    margin-left: 0;
    padding: 20px;
  }

  form {
    flex-direction: column;
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
