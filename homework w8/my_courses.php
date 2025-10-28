<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['id'];

// Add course
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_course'])) {
    $course_name = $_POST['course_name'];
    $course_code = $_POST['course_code'];
    $description = $_POST['description'];
    $year_level = $_POST['year_level'];

    $stmt = $conn->prepare("INSERT INTO courses (course_name, course_code, description, year_level) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $course_name, $course_code, $description, $year_level);
    $stmt->execute();
    $stmt->close();;
}

// Delete course
if (isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    $conn->query("DELETE FROM courses WHERE id = $deleteId");
}

// Fetch courses and group by year_level
$result = $conn->query("SELECT * FROM courses ORDER BY year_level, course_name");
$grouped_courses = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $grouped_courses[$row['year_level']][] = $row;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Courses</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e7f0fd;
            padding: 50px 20px;
            margin: 0;
            color: #2a3d66;
        }

        h2,
        h3 {
            color: #1f2a56;
            font-weight: 700;
            margin-bottom: 20px;
        }

        form {
            background: #ffffff;
            border-radius: 18px;
            padding: 30px 25px;
            max-width: 480px;
            margin: 0 auto 40px auto;
            box-shadow: 0 8px 25px rgba(60, 94, 171, 0.15);
            transition: box-shadow 0.3s ease;
        }

        form:hover {
            box-shadow: 0 12px 40px rgba(60, 94, 171, 0.3);
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 14px 18px;
            margin-bottom: 20px;
            border-radius: 14px;
            border: 2px solid #aab8e7;
            font-size: 16px;
            font-weight: 500;
            background-color: #f9fbff;
            color: #2a3d66;
            box-sizing: border-box;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }

        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #4466ee;
            background-color: #e3eaff;
        }

        input[type="submit"],
        button {
            background-color: #4466ee;
            color: white;
            border: none;
            padding: 14px 22px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 700;
            font-size: 18px;
            box-shadow: 0 6px 20px rgba(68, 102, 238, 0.5);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="submit"]:hover,
        button:hover {
            background-color: #2a3ebb;
            box-shadow: 0 8px 30px rgba(42, 62, 187, 0.7);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            background-color: #fff;
            box-shadow: 0 5px 18px rgba(60, 94, 171, 0.1);
            border-radius: 14px;
            overflow: hidden;
        }

        th,
        td {
            padding: 16px 20px;
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
            padding: 10px 18px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 5px 15px rgba(217, 83, 79, 0.4);
        }

        .delete-btn:hover {
            background-color: #c9302c;
            box-shadow: 0 7px 20px rgba(201, 48, 44, 0.7);
        }
    </style>
</head>

<body>

    <h2>My Courses</h2>

    <!-- Add Course Form -->
    <form method="POST">
        <input type="text" name="course_name" placeholder="Course Name" required><br>
        <input type="text" name="course_code" placeholder="Course Code" required><br>
        <textarea name="description" placeholder="Description (optional)"></textarea><br>
        <select name="year_level" required>
            <option value="">-- Select Year Level --</option>
            <option value="First Year">First Year</option>
            <option value="Second Year">Second Year</option>
            <option value="Third Year">Third Year</option>
            <option value="Fourth Year">Fourth Year</option>
        </select><br>
        <input type="submit" name="add_course" value="Add Course">
        <button type="button" onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
    </form>

    <!-- Grouped Course List -->
    <?php if (!empty($grouped_courses)): ?>
        <?php foreach ($grouped_courses as $year => $courses): ?>
            <h3><?= $year ?> (<?= count($courses) ?> subjects)</h3>
            <table>
                <tr>
                    <th>Course Name</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?= $course['course_name'] ?></td>
                        <td><?= $course['course_code'] ?></td>
                        <td><?= $course['description'] ?></td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Delete this course?');">
                                <input type="hidden" name="delete_id" value="<?= $course['id'] ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No courses added yet.</p>
    <?php endif; ?>

</body>

</html>