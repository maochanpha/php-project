<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Grade Evaluator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h2 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="number"] {
            width: 200px;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <h2> Enter Student Details </h2>
    <form action="" method="POST">
        Name: <input type="text" name="name" required><br>
        Subject 1 Marks: <input type="number" name="subject1" required><br>
        Subject 2 Marks: <input type="number" name="subject2" required><br>
        Subject 3 Marks: <input type="number" name="subject3" required><br>
        <input type="submit" value="Evaluate Grade">
    </form>
</body>

</html>

<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $subject1 = $_POST['subject1'];
    $subject2 = $_POST['subject2'];
    $subject3 = $_POST['subject3'];

    $total = $subject1 + $subject2 + $subject3;
    $percentage = $total / 3;

    // Determine the grade based on the percentage
    if ($percentage >= 90) {
        $grade = "A+";
    } elseif ($percentage >= 80) {
        $grade = "A";
    } elseif ($percentage >= 70) {
        $grade = "B+";
    } elseif ($percentage >= 60) {
        $grade = "B";
    } elseif ($percentage >= 50) {
        $grade = "C";
    } elseif ($percentage >= 40) {
        $grade = "D";
    } else {
        $grade = "F";
    }

    // Display the result
    echo "<h3> Result: </h3>";
    echo "Name: " . $name . "<br>";
    echo "Total Marks: " . $total . "<br>";
    echo "Percentage: " . $percentage . "%<br>";
    echo "Grade: " . $grade . "<br>";
}
?>