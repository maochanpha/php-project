<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "product"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['mark_paid'])) {
        $order_id = intval($_POST['order_id']);
        $conn->query("UPDATE orders SET pay = 'Paid' WHERE id = $order_id");
    }

    if (isset($_POST['remove_order'])) {
        $order_id = intval($_POST['order_id']);
        $conn->query("DELETE FROM orders WHERE id = $order_id");
    }
}

header("Location: admin_dashboard.php"); 
exit;
?>
