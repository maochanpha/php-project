<?php
session_start();
include "db.php";

$result = $conn->query("SELECT * FROM students ORDER BY id DESC");

?>
<!DOCTYPE html>
<html>

<head>
    <title>Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
        }

        table {
            margin: 30px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px 20px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #0074D9;
            color: #fff;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        a {
            display: inline-block;
            margin: 20px;
            text-decoration: none;
            color: #0074D9;
            font-weight: bold;
            padding: 8px 16px;
            border: 1px solid #0074D9;
            border-radius: 4px;
            transition: background 0.2s, color 0.2s;
            text-align: center;
        }

        a:hover {
            background: #0074D9;
            color: #fff;
        }

        .message {
            text-align: center;
            color: green;
            margin-top: 20px;
        }

        button {
            background: #d9534f;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 20px;
        }

        button:hover {
            background: #c9302c;
        }
    </style>
</head>

<body>
    <h2>All Student Results</h2>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p style='color:green'>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    ?>
    <table border="1" cellpadding="10">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Subject 1</th>
            <th>Subject 2</th>
            <th>Subject 3</th>
            <th>Total</th>
            <th>Grade</th>
        </tr>

        <?php
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            $total = $row['total'];
            // Calculate grade
            if ($total >= 90) {
                $grade = "A";
            } elseif ($total >= 80) {
                $grade = "B";
            } elseif ($total >= 70) {
                $grade = "C";
            } elseif ($total >= 60) {
                $grade = "D";
            } elseif ($total >= 50) {
                $grade = "E";
            } else {
                $grade = "F";
            }
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= $row['subject1'] ?></td>
                <td><?= $row['subject2'] ?></td>
                <td><?= $row['subject3'] ?></td>
                <td><?= $total ?></td>
                <td><?= $grade ?></td>
            </tr>
        <?php } ?>
    </table>
    <br>
    <a href="index.php">Go Back</a>
    <form action="clear.php" method="post" onsubmit="return confirm('Are you sure you want to clear all data?');" style="display:inline;">
        <button type="submit">Clear All Data</button>
    </form>
</body>

</html>