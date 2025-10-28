<!DOCTYPE html>
<html lang="en">

<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h2>Welcome, GuestUser</h2>
    <form action="evaluate.php" method="POST">
        <label>Student Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Subject 1 Marks:</label><br>
        <input type="number" name="subject1" required><br>

        <label>Subject 2 Marks:</label><br>
        <input type="number" name="subject2" required><br>

        <label>Subject 3 Marks:</label><br>
        <input type="number" name="subject3" required><br>

        <input type="submit" value="Evaluate">
        <a href="result.php"><button type="button">View Result</button></a>
        <a href="logout.php"><button type="button">Logout</button></a>
    </form>
</body>

</html>