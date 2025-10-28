<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Calcuelate the Number</h2>
    <form action="" method="POST">
        A: <input type="number" name="a" required><br>
        B: <input type="number" name="b" required><br>
        Operator:
        <select name="operator" require>
            <option value="+">+</option>
            <option value="-">-</option>
            <option value="*">*</option>
            <option value="/">/</option>
        </select><br>
        <input type="submit" value="Calulate">
    </form>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST")  {
    $a = $_POST['a'];
    $b = $_POST['b'];
    $operator = $_POST['operator'];

    switch ($operator) {
        case '+':
            $result = $a + $b;
            break;
        case '-':
            $result = $a - $b;
            break;
        case '*':
            $result = $a * $b;
            break;
        case '/':
            if ($b != 0) {
                $result = $a / $b;
            } else {
                $result = "Error: Division by zero";
            }
            break;
        default:
            $result = "Invalid operator";
    }
    
    echo "Result: " . $result;
}
?>