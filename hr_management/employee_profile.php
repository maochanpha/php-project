<?php
include 'db.php';
session_start();

// ✅ Check login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// ✅ Check if "id" exists in URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Employee ID is missing in URL.");
}

$id = intval($_GET['id']); // always cast to int for safety

// ✅ Fetch employee safely
$empQuery = $conn->prepare("SELECT * FROM Employee WHERE EmpID = ?");
$empQuery->bind_param("i", $id);
$empQuery->execute();
$emp = $empQuery->get_result()->fetch_assoc();

if (!$emp) {
    die("❌ Employee not found.");
}

// ✅ Fetch dependents safely
$deps = $conn->prepare("SELECT * FROM Dependents WHERE EmpID = ?");
$deps->bind_param("i", $id);
$deps->execute();
$depsResult = $deps->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
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
    <tr><th>Name</th><th>Relationship</th><th>DOB</th><th>Action</th></tr>
    <?php while($d = $depsResult->fetch_assoc()) { ?>
      <tr>
        <td><?= htmlspecialchars($d['DepName']) ?></td>
        <td><?= htmlspecialchars($d['Relationship']) ?></td>
        <td><?= htmlspecialchars($d['DOB']) ?></td>
        <td>
          <a href="?id=<?= $id ?>&editDep=<?= $d['DepID'] ?>">✏️</a>
          <a href="?id=<?= $id ?>&delDep=<?= $d['DepID'] ?>" onclick="return confirm('Delete dependent?')">❌</a>
        </td>
      </tr>
    <?php } ?>
  </table>

<?php
// ✅ DELETE dependent
if (isset($_GET['delDep'])) {
    $delDep = intval($_GET['delDep']);
    $del = $conn->prepare("DELETE FROM Dependents WHERE DepID=?");
    $del->bind_param("i", $delDep);
    $del->execute();
    header("Location: employee_profile.php?id=$id");
    exit;
}

// ✅ EDIT dependent
if (isset($_GET['editDep'])) {
    $editDep = intval($_GET['editDep']);
    $dep = $conn->query("SELECT * FROM Dependents WHERE DepID=$editDep")->fetch_assoc();
?>
  <h4>Edit Dependent</h4>
  <form method="POST">
    <input type="text" name="DepName" value="<?= htmlspecialchars($dep['DepName']) ?>" required>
    <input type="text" name="Relationship" value="<?= htmlspecialchars($dep['Relationship']) ?>" required>
    <input type="date" name="DOB" value="<?= htmlspecialchars($dep['DOB']) ?>" required>
    <button type="submit" name="updateDep">Update</button>
  </form>
<?php
}
if (isset($_POST['updateDep'])) {
    $update = $conn->prepare("UPDATE Dependents SET DepName=?, Relationship=?, DOB=? WHERE DepID=?");
    $update->bind_param("sssi", $_POST['DepName'], $_POST['Relationship'], $_POST['DOB'], $_GET['editDep']);
    $update->execute();
    header("Location: employee_profile.php?id=$id");
    exit;
}
?>

  <h4>Add Dependent</h4>
  <form method="POST">
    <input type="text" name="DepName" placeholder="Name" required>
    <input type="text" name="Relationship" placeholder="Relationship" required>
    <input type="date" name="DOB" required>
    <button type="submit" name="addDep">Add</button>
  </form>

<?php
// ✅ ADD dependent
if (isset($_POST['addDep'])) {
    $add = $conn->prepare("INSERT INTO Dependents (EmpID, DepName, Relationship, DOB) VALUES (?, ?, ?, ?)");
    $add->bind_param("isss", $id, $_POST['DepName'], $_POST['Relationship'], $_POST['DOB']);
    $add->execute();
    header("Location: employee_profile.php?id=$id");
    exit;
}
?>
</section>
</div>
</body>
</html>
