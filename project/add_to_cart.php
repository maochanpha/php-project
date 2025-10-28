<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';
    $name = $_POST['name'] ?? '';
    $price = floatval($_POST['price'] ?? 0);
    $qty = intval($_POST['qty'] ?? 1);
    $action = $_POST['action'] ?? 'add'; // add or update

    if (!$code || !$name || $price <= 0 || $qty <= 0) {
        die("Invalid product data.");
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['code'] === $code) {
            if ($action === 'update') {
                $item['qty'] = $qty;
            } else {
                $item['qty'] += $qty;
            }
            $found = true;
            break;
        }
    }
    unset($item); // Break reference

    if (!$found) {
        $_SESSION['cart'][] = [
            'code' => $code,
            'name' => $name,
            'price' => $price,
            'qty' => $qty
        ];
    }

    header("Location: cart.php");
    exit;
} else {
    echo "Invalid request.";
}
?>
<form action="add_to_cart.php" method="POST">
    <input type="hidden" name="code" value="P001">
    <input type="hidden" name="name" value="TUF A15">
    <input type="hidden" name="price" value="999">
    <input type="number" name="qty" value="1" min="1">
    <input type="hidden" name="action" value="add"> <!-- or "update" -->
    <button type="submit">Add to Cart</button>
</form>
