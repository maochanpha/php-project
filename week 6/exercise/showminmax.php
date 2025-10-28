<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Find Min/Max</h2>
    <form action="" method="POST">
        A: <input type="number" name="a" required><br>
        B: <input type="number" name="b" required><br>
        C: <input type="number" name="c" required><br>
        <input type="submit" value="Show Min/Max">
    </form>

</body>

</html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $a = $_POST['a'];
    $b = $_POST['b'];
    $c = $_POST['c'];

    if ($a <= $b && $a <= $c) {
        $min = $a;
    } elseif ($b <= $a && $b <= $c) {
        $min = $b;
    } else {
        $min = $c;
    }

    if ($a >= $b && $a >= $c) {
        $max = $a;
    } elseif ($b >= $a && $b >= $c) {
        $max = $b;
    } else {
        $max = $c;
    }

    echo "Minimum: " . $min . "<br>";
    echo "Maximum: " . $max;
}
?>