<?php
session_start();

if (empty($_SESSION['cart'])) {
    die("<h3 style='text-align:center;color:red;'>Your cart is empty.</h3>");
}

$name = isset($_SESSION['user']) ? $_SESSION['user'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$phone = isset($_SESSION['phone']) ? $_SESSION['phone'] : '';
$cart = $_SESSION['cart'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Checkout</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #fff3ee;
            margin: 0;
            padding: 40px;
            color: #333;
            min-height: 100vh;
        }

        h1,
        h2 {
            text-align: center;
            color: #ff6f3c;
            margin-bottom: 30px;
        }

        button,
        .btn,
        input[type="submit"] {
            background-color: #ff6f3c;
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        button:hover,
        .btn:hover,
        input[type="submit"]:hover {
            background-color: #e65c28;
        }

        a.btn {
            color: white;
        }

        table {
            width: 90%;
            margin: 0 auto 30px auto;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 14px 20px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #ff6f3c;
            color: white;
            font-weight: 600;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .total-row {
            font-weight: bold;
            background-color: #fbe2d6;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="number"]:focus,
        textarea:focus,
        select:focus {
            border-color: #ff6f3c;
            outline: none;
        }

        .remove-btn {
            background-color: #f44336;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .remove-btn:hover {
            background-color: #d32f2f;
        }

        .message,
        .confirmation {
            text-align: center;
            font-size: 18px;
            color: #888;
            margin-top: 40px;
        }

        .confirmation img {
            width: 200px;
            height: 200px;
            margin-top: 20px;
        }

        a {
            text-decoration: none;
            color: white;
        }

        .top-left-home {
            position: absolute;
            top: 20px;
            left: 20px;
        }
    </style>
</head>

<body>

    <h2 style="text-align:center;">Checkout</h2>

    <form method="post" action="process_order.php" style="max-width: 400px; margin:auto;">
        <label for="custname">Name:</label>
        <input type="text" name="custname" id="custname" required value="<?php echo htmlspecialchars($name); ?>">

        <label for="phone">Phone:</label>
        <input type="text" name="phone" id="phone" required value="<?php echo htmlspecialchars($phone); ?>">

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($email); ?>">

        <label for="address">Address:</label>
        <textarea name="address" id="address" rows="3" required></textarea>

        <label for="payment">Payment Method:</label>
        <select name="payment" id="payment" required>
            <option value="Cash">Cash</option>
            <option value="QR Code">QR Code</option>
        </select>

        <button type="submit" name="confirm">Place Order</button>
    </form>

    <div class="top-left-home">
        <a href="index.php" class="btn">Home</a>
    </div>

</body>

</html>
