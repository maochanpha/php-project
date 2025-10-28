<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Handle Delete One Grade
if (isset($_POST['delete']) && isset($_POST['delete_id'])) {
    $deleteId = (int)$_POST['delete_id'];
    $conn->query("DELETE FROM grades WHERE id = $deleteId");
}

// Handle Clear All Grades
if (isset($_POST['clear'])) {
    $conn->query("DELETE FROM grades");
}

// Handle Add Grade
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_grade'])) {
    $student = $_POST['student_name'];
    $course = $_POST['course'];
    $midterm = (int)$_POST['midterm'];
    $final = (int)$_POST['final'];
    $total = $midterm + $final;

    // Assign letter grade
    if ($total >= 90) $grade = 'A';
    elseif ($total >= 80) $grade = 'B';
    elseif ($total >= 70) $grade = 'C';
    elseif ($total >= 60) $grade = 'D';
    else $grade = 'F';

    $conn->query("INSERT INTO grades (student_name, course, midterm, final, total, letter_grade) 
                VALUES ('$student', '$course', $midterm, $final, $total, '$grade')");
}

// Fetch grades
$grades = $conn->query("SELECT * FROM grades");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Grade Management</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 40px 20px;
            background: linear-gradient(to right, #e3f2fd, #f9fbff);
            color: #1a2a52;
        }

        h2,
        h3 {
            color: #1a2a52;
            font-weight: 700;
            margin-bottom: 18px;
            text-align: center;
        }

        form {
            background-color: #ffffff;
            max-width: 620px;
            margin: 0 auto 30px auto;
            padding: 32px 36px;
            border-radius: 20px;
            box-shadow: 0 8px 28px rgba(60, 94, 171, 0.12);
            transition: 0.3s ease;
        }

        form:hover {
            box-shadow: 0 12px 38px rgba(60, 94, 171, 0.24);
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 14px 18px;
            margin: 14px 0;
            border: 2px solid #bbcdfd;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 500;
            color: #1a2a52;
            background-color: #f7faff;
            transition: 0.3s ease, 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="number"]:focus {
            outline: none;
            border-color: #3a64e8;
            background-color: #e6efff;
        }

        input[type="submit"],
        button {
            padding: 14px 28px;
            margin-top: 16px;
            margin-right: 10px;
            border: none;
            border-radius: 28px;
            font-weight: 700;
            font-size: 17px;
            cursor: pointer;
            color: #fff;
            box-shadow: 0 7px 22px rgba(58, 100, 232, 0.45);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="submit"] {
            background-color: #3a64e8;
        }

        input[type="submit"]:hover {
            background-color: #2e50bf;
            box-shadow: 0 9px 30px rgba(46, 80, 191, 0.65);
        }

        button {
            background-color: #3a64e8;
            box-shadow: 0 5px 15px rgba(156, 166, 255, 0.5);
        }

        button:hover {
            background-color: #2e50bf;
            box-shadow: 0 7px 22px rgba(127, 140, 255, 0.75);
        }

        .clear-btn {
            background-color: #ff7043;
            box-shadow: 0 7px 22px rgba(255, 112, 67, 0.65);
            margin: 20px auto 0 auto;
            display: block;
            padding: 14px 36px;
            border-radius: 28px;
            font-weight: 700;
            font-size: 17px;
            color: white;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .clear-btn:hover {
            background-color: #e65c2f;
            box-shadow: 0 9px 30px rgba(230, 92, 47, 0.85);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 7px 25px rgba(60, 94, 171, 0.12);
            overflow: hidden;
        }

        th,
        td {
            padding: 18px 24px;
            text-align: left;
            border-bottom: 1px solid #e3e9ff;
            font-weight: 500;
            color: #1a2a52;
        }

        th {
            background-color: #dbe4ff;
            font-weight: 700;
            letter-spacing: 0.06em;
        }

        tr:hover {
            background-color: #f3f8ff;
        }

        .delete-btn {
            background-color: #ff5c5c;
            color: white;
            padding: 10px 20px;
            font-weight: 600;
            border-radius: 20px;
            border: none;
            cursor: pointer;
            box-shadow: 0 7px 22px rgba(255, 92, 92, 0.5);
            transition: 0.3s ease, 0.3s ease;
        }

        .delete-btn:hover {
            background-color: #e04848;
            box-shadow: 0 9px 30px rgba(224, 72, 72, 0.75);
        }

        .actions form {
            display: inline;
        }
    </style>

</head>

<body>

    <h2>Grade Entry</h2>
    <form method="POST">
        <input name="student name" type="text" placeholder="Student Name" required><br>
        <input name="course" type="text" placeholder="Course" required><br>
        <input name="midterm" type="number" placeholder="Midterm Score" required><br>
        <input name="final" type="number" placeholder="Final Score" required><br>
        <input type="submit" name="add_grade" value="Add Grade">
        <button onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
    </form>

    <!-- Clear All Grades -->
    <form method="POST" onsubmit="return confirm('Are you sure you want to clear all grade data?');">
        <input type="submit" name="clear" value="Clear All Grades" class="clear-btn">
    </form>

    <h3>All Grades</h3>
    <table>
        <tr>
            <th>Student</th>
            <th>Course</th>
            <th>Midterm</th>
            <th>Final</th>
            <th>Total</th>
            <th>Grade</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $grades->fetch_assoc()): ?>
            <tr>
                <td><?= $row['student_name'] ?></td>
                <td><?= $row['course'] ?></td>
                <td><?= $row['midterm'] ?></td>
                <td><?= $row['final'] ?></td>
                <td><?= $row['total'] ?></td>
                <td><?= $row['letter_grade'] ?></td>
                <td>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this grade?');">
                        <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                        <button type="submit" name="delete" class="delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

</body>

</html>