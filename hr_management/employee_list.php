<?php
session_start();

// ✅ Require login first
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// ===============================
// Database connection
// ===============================
$servername = "localhost";
$username   = "root";      // change if needed
$password   = "";          // your MySQL password
$dbname     = "hr_management";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all employees
$sql = "SELECT EmpID, FirstName, LastName, Gender, Email, Salary FROM Employee ORDER BY EmpID ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
        }
        .message {
            width: 90%;
            margin: 10px auto;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 20px auto;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #2980b9;
            color: white;
        }
        tr:hover {
            background: #f1f1f1;
        }
        a.btn {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }
        .edit {
            background-color: #27ae60;
            color: white;
        }
        .delete {
            background-color: #e74c3c;
            color: white;
        }
        .add-btn {
            display: inline-block;
            margin: 10px auto;
            background-color: #3498db;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
        }
        .center {
            text-align: center;
        }
        .logout {
            display: inline-block;
            margin-left: 10px;
            background: #e67e22;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1>Employee List</h1>

    <!-- ✅ Success / Error messages -->
    <?php if (isset($_GET['msg'])): ?>
        <?php if ($_GET['msg'] == 'deleted'): ?>
            <div class="message success">✅ Employee deleted successfully!</div>
        <?php elseif ($_GET['msg'] == 'error'): ?>
            <div class="message error">❌ Error deleting employee. Try again.</div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="center">
        <a href="add_employee.php" class="add-btn">+ Add New Employee</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Salary</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['EmpID']; ?></td>
                        <td><?php echo htmlspecialchars($row['FirstName']); ?></td>
                        <td><?php echo htmlspecialchars($row['LastName']); ?></td>
                        <td><?php echo htmlspecialchars($row['Gender']); ?></td>
                        <td><?php echo htmlspecialchars($row['Email']); ?></td>
                        <td><?php echo number_format($row['Salary'], 2); ?></td>
                        <td>
                            <a href="edit_employee.php?EmpID=<?php echo $row['EmpID']; ?>" class="btn edit">Edit</a>
                            <a href="delete_employee.php?EmpID=<?php echo $row['EmpID']; ?>" 
                               class="btn delete" 
                               onclick="return confirm('Are you sure you want to delete this employee?');">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7">No employees found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
<?php $conn->close(); ?>
