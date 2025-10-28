<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle POST requests for update or remove
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove_code'])) {
        $remove_code = $_POST['remove_code'];
        if (isset($_SESSION['cart'][$remove_code])) {
            unset($_SESSION['cart'][$remove_code]);
        }
    }

    if (isset($_POST['update_code'], $_POST['new_qty'])) {
        $update_code = $_POST['update_code'];
        $new_qty = max(1, (int)$_POST['new_qty']);
        if (isset($_SESSION['cart'][$update_code])) {
            $_SESSION['cart'][$update_code]['quantity'] = $new_qty;
        }
    }

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Your Cart</title>
    <style>
* {
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
}

body {
    background-color: #fff8f5;
    padding: 40px 20px;
    color: #444;
    min-height: 100vh;
}

h1 {
    text-align: center;
    color: #e55d3d;
    font-weight: 900;
    margin-bottom: 40px;
    font-size: 2.8rem;
    letter-spacing: 2px;
}

table {
    width: 90%;
    margin: 0 auto 40px auto;
    border-collapse: separate;
    border-spacing: 0 12px;
    background-color: transparent;
}

thead tr {
    background-color: #e75c35;
    color: white;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.95rem;
    border-radius: 12px;
}

thead tr th {
    padding: 14px 12px;
    border: none;
}

tbody tr {
    background-color: #fff;
    box-shadow: 0 4px 10px rgba(231, 92, 53, 0.15);
    border-radius: 12px;
    transition: transform 0.2s ease;
}

tbody tr:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 18px rgba(231, 92, 53, 0.25);
}

tbody tr td {
    padding: 14px 12px;
    text-align: center;
    vertical-align: middle;
    border: none;
    font-size: 1rem;
    color: #555;
}

tbody tr td strong {
    font-weight: 700;
}

input[type="number"] {
    width: 70px;
    padding: 6px 8px;
    font-size: 1rem;
    border-radius: 8px;
    border: 1.8px solid #e75c35;
    text-align: center;
    transition: border-color 0.3s ease;
}

input[type="number"]:focus {
    outline: none;
    border-color: #ba4128;
}

button {
    background-color: #e75c35;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 700;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
    font-size: 0.9rem;
}

button:hover {
    background-color: #ba4128;
    transform: scale(1.05);
}

a {
    display: inline-block;
    background-color: #e75c35;
    color: white;
    text-decoration: none;
    padding: 12px 28px;
    margin: 0 10px;
    border-radius: 24px;
    font-weight: 700;
    font-size: 1rem;
    box-shadow: 0 6px 18px rgba(231, 92, 53, 0.3);
    transition: background-color 0.3s ease, transform 0.2s ease;
    text-align: center;
}

a:hover {
    background-color: #ba4128;
    transform: scale(1.1);
}

p {
    text-align: center;
    font-size: 1.2rem;
    color: #888;
    margin-top: 40px;
}

/* Responsive */
@media (max-width: 768px) {
    table {
        width: 100%;
        border-spacing: 0 10px;
    }

    input[type="number"] {
        width: 50px;
        font-size: 0.9rem;
    }

    button, a {
        font-size: 0.9rem;
        padding: 8px 18px;
        margin: 6px 6px 0 0;
    }

    tbody tr td {
        font-size: 0.9rem;
        padding: 10px 8px;
    }
}

    </style>
</head>

<body>

    <h1>Your Shopping Cart</h1>

    <?php if (!empty($_SESSION['cart'])): ?>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $code => $item):
                    $name = htmlspecialchars($item['name']);
                    $qty = $item['quantity'] ?? 1;
                    $price = (float)$item['price'];
                    $subtotal = $qty * $price;
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($code) ?></td>
                    <td><?= $name ?></td>
                    <td>
                        <form method="post" style="display:inline-flex; gap: 5px; align-items:center;">
                            <input type="number" name="new_qty" value="<?= $qty ?>" min="1" required>
                            <input type="hidden" name="update_code" value="<?= htmlspecialchars($code) ?>">
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td>$<?= number_format($price, 2) ?></td>
                    <td>$<?= number_format($subtotal, 2) ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="remove_code" value="<?= htmlspecialchars($code) ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4" style="text-align:right;"><strong>Total:</strong></td>
                    <td colspan="2"><strong>$<?= number_format($total, 2) ?></strong></td>
                </tr>
            </tbody>
        </table>
        <a href="product.php">Continue Shopping</a> | <a href="checkout.php">Proceed to Checkout</a>
    <?php else: ?>
        <p>Your cart is empty.</p>
        <a href="product.php">Return to Store</a>
    <?php endif; ?>
</body>
</html>