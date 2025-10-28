<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "product"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$message = "";

// Handle form submission for adding product
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_product'])) {
    // Sanitize inputs
    $shop = mysqli_real_escape_string($conn, $_POST['shop']);
    $product_code = mysqli_real_escape_string($conn, $_POST['product_code']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $qty = mysqli_real_escape_string($conn, $_POST['qty']);

    if (empty($shop) || empty($product_code) || empty($name) || empty($price) || empty($qty)) {
        $message = "<p class='message error'>❌ Please fill in all required fields.</p>";
    } else {
        // Handle image upload
        $image = "";
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $image = $targetDir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $image);
        }

        // Insert into database
        $sql = "INSERT INTO products (shop_name, product_code, name, price, qty, image) 
                VALUES ('$shop', '$product_code', '$name', '$price', '$qty', '$image')";

        if ($conn->query($sql) === TRUE) {
            $message = "<p class='message success'>✅ New product added for $shop!</p>";
        } else {
            if ($conn->errno == 1062) {
                $message = "<p class='message error'>❌ Product code already exists! Please use a different code.</p>";
            } else {
                $message = "<p class='message error'>❌ Error: " . $conn->error . "</p>";
            }
        }
    }
}

// Handle form submission for removing product
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['remove_product'])) {
    $remove_code = mysqli_real_escape_string($conn, $_POST['remove_product_code']);
    if (!empty($remove_code)) {
        $delete_sql = "DELETE FROM products WHERE product_code = '$remove_code'";
        if ($conn->query($delete_sql) === TRUE) {
            $message = "<p class='message success'>✅ Product $remove_code removed successfully.</p>";
        } else {
            $message = "<p class='message error'>❌ Error removing product: " . $conn->error . "</p>";
        }
    } else {
        $message = "<p class='message error'>❌ Please select a product to remove.</p>";
    }
}

// Fetch all products for remove dropdown
$product_result = $conn->query("SELECT product_code, name, shop_name FROM products ORDER BY shop_name, product_code");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Panel - Add / Remove Product</title>
    <style>
body {
    font-family: 'Urbanist', sans-serif;
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    color: #e5e5e5;
    margin: 0;
    padding: 0;
}
header {
    background: #1fd1f9;
    background: linear-gradient(90deg, #1fd1f9 0%, #b621fe 100%);
    color: #fff;
    text-align: center;
    padding: 32px;
    font-size: 2.4rem;
    font-weight: 800;
    letter-spacing: 1px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}
.container {
    max-width: 760px;
    background: #121212;
    margin: 70px auto;
    padding: 45px;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5);
}
h2 {
    text-align: center;
    margin-bottom: 35px;
    color: #1fd1f9;
    font-size: 1.9rem;
}
form label {
    display: block;
    margin-bottom: 12px;
    font-weight: 600;
    color: #b0b0b0;
}
input[type="text"],
input[type="number"],
input[type="file"],
select {
    width: 100%;
    padding: 14px;
    margin-bottom: 26px;
    border-radius: 12px;
    border: none;
    background: #1e1e1e;
    color: #fff;
    font-size: 1rem;
    transition: all 0.25s ease-in-out;
}
input:focus,
select:focus {
    background: #292929;
    border: 1px solid #1fd1f9;
    outline: none;
    box-shadow: 0 0 5px #1fd1f9;
}
button {
    background: linear-gradient(90deg, #1fd1f9 0%, #b621fe 100%);
    color: white;
    border: none;
    padding: 16px;
    width: 100%;
    border-radius: 14px;
    font-size: 1.2rem;
    font-weight: 700;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.3s;
}
button:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 20px rgba(31, 209, 249, 0.4);
}
.message {
    text-align: center;
    font-size: 1.2rem;
    margin-bottom: 20px;
}
.success {
    color: #00e676;
}
.error {
    color: #ff5252;
}
.back-link {
    display: block;
    text-align: center;
    margin-top: 30px;
    color: #1fd1f9;
    text-decoration: none;
    font-weight: 700;
    transition: color 0.3s;
}
.back-link:hover {
    color: #b621fe;
}
form + form {
    margin-top: 55px;
}

    </style>
</head>
<body>
    <header>Admin Panel - Manage Products</header>
    <div class="container">

        <?php echo $message; ?>

        <!-- Add Product Form -->
        <h2>Add Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="shop">Select Shop:</label>
            <select name="shop" id="shop" required>
                <option value="">-- Select Shop --</option>
                <option value="Zando">Zando</option>
            </select>

            <label for="product_code">Product Code:</label>
            <input type="text" name="product_code" id="product_code" required>

            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="price">Price:</label>
            <input type="number" name="price" step="0.01" id="price" required>

            <label for="qty">Quantity:</label>
            <input type="number" name="qty" id="qty" required>

            <label for="image">Product Image:</label>
            <input type="file" name="image" id="image">

            <button type="submit" name="add_product">Add Product</button>
        </form>

        <!-- Remove Product Form -->
        <h2>Remove Product</h2>
        <form method="POST">
            <label for="remove_product_code">Select Product to Remove:</label>
            <select name="remove_product_code" id="remove_product_code" required>
                <option value="">-- Select Product --</option>
                <?php if ($product_result && $product_result->num_rows > 0): ?>
                    <?php while ($prod = $product_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($prod['product_code']) ?>">
                            <?= htmlspecialchars($prod['shop_name']) ?> - <?= htmlspecialchars($prod['product_code']) ?> - <?= htmlspecialchars($prod['name']) ?>
                        </option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="">No products available</option>
                <?php endif; ?>
            </select>
            <button type="submit" name="remove_product" onclick="return confirm('Are you sure you want to remove this product?');">Remove Product</button>
        </form>

        <a href="admin_dashboard.php" class="back-link">⬅ Back to Dashboard</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
