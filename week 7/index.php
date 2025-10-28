<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Welcome, GuestUser!</h1><br>

    <form action="" method="POST">
        <h2>Enter The Student Score</h2><br>
        <input type="text" name="name" placeholder="Name" required><br>
        <input type="number" name="attendance" placeholder="Attendance" required><br>
        <input type="number" name="quiz" placeholder="Quiz" required><br>
        <input type="number" name="midterm" placeholder="Midterm" required><br>
        <input type="number" name="final" placeholder="Final" required><br>
        <button value="submit">Submit</button><br>
    </form>


    <?php
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'test';

    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $attendance = (int)$_POST['attendance'];
        $quiz = (int)$_POST['quiz'];
        $midterm = (int)$_POST['midterm'];
        $final = (int)$_POST['final'];

        $total = $attendance + $quiz + $midterm + $final;
        $average = $total / 4;

        if ($total >= 90) {
            $grade = 'A';
        } elseif ($total >= 80) {
            $grade = 'B';
        } elseif ($total >= 70) {
            $grade = 'C';
        } elseif ($total >= 60) {
            $grade = 'D';
        } elseif ($total >= 50) {
            $grade = 'E';
        } else {
            $grade = 'F';
        }

        $sql = "INSERT INTO test2 (name, attendance, quiz, midterm, final, total, average, grade) 
    VALUES ('$name', $attendance, $quiz, $midterm, $final, $total, $average, '$grade')";
        if ($conn->query($sql) === TRUE) {
            echo "<h2>Evaluation Result</h2>";
            echo "Name: $name <br>";
            echo "Grade: $grade <br>";
            echo "Total: $total <br>";
            echo "Average: $average <br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    $conn->close();
    ?>
</body>

</html>