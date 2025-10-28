<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db = "product"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    $custName = $_POST['custname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $payment = $_POST['payment'];
    $cart = $_SESSION['cart'] ?? [];

    if (empty($cart)) {
        die("<h3 style='text-align:center;color:red;'>Your cart is empty.</h3>");
    }

    $stmtCustomer = $conn->prepare("INSERT INTO TbCustomer (CustName, Phone, Email) VALUES (?, ?, ?)");
    $stmtCustomer->bind_param("sss", $custName, $phone, $email);
    if (!$stmtCustomer->execute()) {
        die("Error inserting customer: " . $stmtCustomer->error);
    }

    foreach ($cart as $item) {
        $code = $item['code'];
        $name = $item['name'];
        $price = $item['price'];
        $qty = $item['qty'];
        $total = $price * $qty;

        $stmtProduct = $conn->prepare("INSERT INTO TbProduct (ProductCode, ProName, Price, Qty) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE Qty = Qty + ?");
        $stmtProduct->bind_param("ssdii", $code, $name, $price, $qty, $qty);
        if (!$stmtProduct->execute()) {
            echo "<p>Error inserting product: {$stmtProduct->error}</p>";
        }

        $stmtSale = $conn->prepare("INSERT INTO TbSale (CustName, ProCode, Qty, Total, PaymentMethod) VALUES (?, ?, ?, ?, ?)");
        $stmtSale->bind_param("ssiis", $custName, $code, $qty, $total, $payment);
        if (!$stmtSale->execute()) {
            echo "<p>Error inserting sale: {$stmtSale->error}</p>";
        }
    }


    unset($_SESSION['cart']);

    echo "<h2 style='text-align:center; color:green;'>✅ Your order has been placed successfully!</h2>";


    if ($payment === "QR Code") {
        echo '<div style="text-align:center; margin-top: 20px;">';
        echo '<h3>Please scan this QR code to complete payment:</h3>';
        echo '<img src="qrcode.png" alt="QR Code" style="width:200px; height:200px;">';
        echo '</div>';
    }

    echo "<div style='text-align:center; margin-top: 20px;'><a href='product.html' style='color:#ff6f3c; text-decoration:none; font-size:18px;'>Continue Shopping</a></div>";
} else {
    echo "<h3 style='text-align:center;color:red;'>Invalid access.</h3>";
}
