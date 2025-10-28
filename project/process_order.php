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
    $custName = trim($_POST['custname']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $payment = $_POST['payment'];
    $cart = $_SESSION['cart'] ?? [];

    if (empty($cart)) {
        die("<h3 style='text-align:center;color:red;'>Your cart is empty.</h3>");
    }

    $conn->begin_transaction();

    try {
        // Insert customer
        $stmtCustomer = $conn->prepare("INSERT INTO TbCustomer (CustName, Phone, Email) VALUES (?, ?, ?)");
        $stmtCustomer->bind_param("sss", $custName, $phone, $email);
        $stmtCustomer->execute();

        $stmtProduct = $conn->prepare("INSERT INTO TbProduct (ProductCode, ProName, Price, Qty) VALUES (?, ?, ?, ?) 
                                    ON DUPLICATE KEY UPDATE Qty = Qty - VALUES(Qty)");
        $stmtSale = $conn->prepare("INSERT INTO TbSale (CustName, ProCode, Qty, Total, PaymentMethod) VALUES (?, ?, ?, ?, ?)");
        $stmtOrder = $conn->prepare("INSERT INTO orders (customer_name, product_name, quantity, total_price, order_date) VALUES (?, ?, ?, ?, NOW())");

        foreach ($cart as $code => $item) {
            $name = $item['name'];
            $price = $item['price'];
            $qty = $item['quantity'] ?? 1;  // Use 'quantity' key here
            $total = $price * $qty;

            // Update product (or insert new if not exists)
            $stmtProduct->bind_param("ssdi", $code, $name, $price, $qty);
            if (!$stmtProduct->execute()) {
                throw new Exception("Error inserting/updating product: " . $stmtProduct->error);
            }

            // Insert into TbSale
            $stmtSale->bind_param("ssiis", $custName, $code, $qty, $total, $payment);
            if (!$stmtSale->execute()) {
                throw new Exception("Error inserting sale: " . $stmtSale->error);
            }

            // Insert into orders (admin panel)
            $stmtOrder->bind_param("ssii", $custName, $name, $qty, $total);
            if (!$stmtOrder->execute()) {
                throw new Exception("Error inserting order: " . $stmtOrder->error);
            }
        }

        // Commit transaction
        $conn->commit();
        unset($_SESSION['cart']); // Clear cart

        echo "<h2 style='text-align:center; color:green;'>✅ Your order has been placed successfully!</h2>";

        if ($payment === "QR Code") {
            echo '<div style="text-align:center; margin-top: 20px;">';
            echo '<h3>Please scan this QR code to complete payment:</h3>';
            echo '<img src="qrcode.png" alt="QR Code" style="width:200px; height:200px;">';
            echo '</div>';
        }

        echo "<div style='text-align:center; margin-top: 20px;'><a href='product.php' style='color:#ff6f3c; text-decoration:none; font-size:18px;'>Continue Shopping</a></div>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "<h3 style='text-align:center; color:red;'>Order failed: " . htmlspecialchars($e->getMessage()) . "</h3>";
    }
} else {
    echo "<h3 style='text-align:center;color:red;'>Invalid access.</h3>";
}

$conn->close();
