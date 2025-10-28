<?php
session_start();
include 'db.php';

// Add student
if (isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $gender = trim($_POST['gender']);

    if (!empty($name) && !empty($gender)) {
        $stmt = $conn->prepare("INSERT INTO students (name, gender) VALUE (?, ?)");
        $stmt->bind_param("ss", $name, $gender);
        $stmt->execute();
    }
}

// Delete one student
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $conn->query("DELETE FROM students WHERE id = $id");
}

// Clear all students
if (isset($_POST['clear_all'])) {
    $conn->query("DELETE FROM students");
}

// Fetch all students
$students = [];
$result = $conn->query("SELECT * FROM students");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Student List</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e7f0fd;
            padding: 40px 20px;
            margin: 0;
            color: #2a3d66;
        }

        .container {
            max-width: 850px;
            margin: 0 auto;
            background: #fff;
            padding: 35px 40px;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(60, 94, 171, 0.15);
            transition: box-shadow 0.3s ease;
        }

        .container:hover {
            box-shadow: 0 14px 45px rgba(60, 94, 171, 0.3);
        }

        h2 {
            color: #1f2a56;
            font-weight: 700;
            margin-bottom: 35px;
            font-size: 2rem;
            text-align: center;
        }

        form.form-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-bottom: 40px;
        }

        form.form-group input[type="text"] {
            flex: 1 1 200px;
            padding: 14px 16px;
            border: 2px solid #aab8e7;
            border-radius: 14px;
            font-size: 16px;
            transition: border-color 0.3s ease, background-color 0.3s ease;
            background-color: #f9fbff;
            color: #2a3d66;
        }

        form.form-group input[type="text"]:focus {
            outline: none;
            border-color: #4466ee;
            background-color: #e3eaff;
        }

        form.form-group button {
            padding: 14px 28px;
            background-color: #4466ee;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 6px 20px rgba(68, 102, 238, 0.5);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        form.form-group button:hover {
            background-color: #2a3ebb;
            box-shadow: 0 8px 30px rgba(42, 62, 187, 0.7);
        }

        button[onclick] {
            background-color: #999;
            box-shadow: none;
        }

        button[onclick]:hover {
            background-color: #777;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 5px 18px rgba(60, 94, 171, 0.1);
            background-color: #fff;
        }

        th,
        td {
            padding: 16px 22px;
            text-align: left;
            border-bottom: 1px solid #cfd9ff;
            font-weight: 500;
            color: #2a3d66;
        }

        th {
            background-color: #aab8e7;
            color: #1f2a56;
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        .delete-btn {
            background-color: #d9534f;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 5px 15px rgba(217, 83, 79, 0.4);
        }

        .delete-btn:hover {
            background-color: #c9302c;
            box-shadow: 0 7px 20px rgba(201, 48, 44, 0.7);
        }

        .clear-btn {
            background-color: #f0ad4e;
            color: #3e3e3e;
            border: none;
            border-radius: 18px;
            padding: 14px 26px;
            cursor: pointer;
            font-weight: 700;
            font-size: 18px;
            box-shadow: 0 6px 20px rgba(240, 173, 78, 0.5);
            margin-top: 35px;
            display: block;
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .clear-btn:hover {
            background-color: #d69020;
            box-shadow: 0 8px 30px rgba(214, 144, 32, 0.7);
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Student List</h2>

        <!-- Add Student Form -->
        <form method="POST" class="form-group">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="gender" placeholder="Gender" required>
            <button type="submit" name="add">Add Student</button>
            <button type="button" onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
        </form>

        <!-- Student Table -->
        <table>
            <tr>
                <th>No.</th>
                <th>Full Name</th>
                <th>Gender</th>
                <th>Action</th>
            </tr>
            <?php foreach ($students as $index => $student): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $student['name'] ?></td>
                    <td><?= $student['gender'] ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $student['id'] ?>">
                            <button type="submit" name="delete" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Clear All Button -->
        <?php if (!empty($students)): ?>
            <form method="POST">
                <button type="submit" name="clear_all" class="clear-btn">Clear All Students</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>