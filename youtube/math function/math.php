<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="" method="POST">
        <label>x:</label>
        <input type="text" name="x">
        <input type="submit" value="total">
    </form>

    <?php
    // Check if the form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get value safely
        $x = $_POST["x"];
        $total = $x; // Do your processing here (if needed)
        echo "Total: $total";
    }
    ?>
</body>

</html>
