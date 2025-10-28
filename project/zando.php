    <?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
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
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_to_cart'])) {
    $code = $_POST['product_code'];

    $stmt = $conn->prepare("SELECT name, price FROM products WHERE product_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($product = $result->fetch_assoc()) {
        $item = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => 1
        ];

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$code])) {
            $_SESSION['cart'][$code]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$code] = $item;
        }

        $message = "<p class='message success'>Product <strong>" . htmlspecialchars($product['name']) . "</strong> added to cart.</p>";
    } else {
        $message = "<p class='message error'>Product not found.</p>";
    }
    $stmt->close();
}


    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['remove_product'])) {
        $remove_code = $conn->real_escape_string($_POST['product_code']);
        $delete_sql = "DELETE FROM products WHERE product_code = '$remove_code' AND shop_name = 'Zando'";

        if ($conn->query($delete_sql) === TRUE) {
            $message = "<p class='message success'>Product $remove_code removed successfully.</p>";
        } else {
            $message = "<p class='message error'>Error removing product: " . $conn->error . "</p>";
        }
    }

    $sql = "SELECT * FROM products WHERE shop_name = 'Zando'";
    $result = $conn->query($sql);
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Maison D&G Store - Zando</title>
        <style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    }

    body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #fff9f7;
    color: #3a3a3a;
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
    }

    header {
    background: linear-gradient(90deg, #ff6a53 0%, #d7412a 100%);
    color: #fff;
    padding: 24px 0;
    text-align: center;
    position: sticky;
    top: 0;
    z-index: 999;
    box-shadow: 0 8px 30px rgba(255, 106, 83, 0.3);
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
    transition: background 0.3s ease;
    }

    header h1 {
    font-size: 2.4rem;
    font-weight: 900;
    letter-spacing: 3px;
    text-transform: uppercase;
    transition: transform 0.3s ease;
    cursor: default;
    }

    header h1:hover {
    transform: scale(1.1) rotate(-2deg);
    }


    nav {
    margin-top: 14px;
    text-align: center;
    }

    nav a {
    color: #fff5f3;
    margin: 0 14px;
    font-weight: 700;
    font-size: 1rem;
    padding-bottom: 5px;
    text-decoration: none;
    border-bottom: 3px solid transparent;
    transition: color 0.3s ease, border-bottom 0.3s ease;
    }

    nav a:hover,
    nav a:focus {
    color: #2c130f;
    border-bottom: 3px solid #2c130f;
    outline: none;
    }


    #Zando,
    h2 {
    text-align: center;
    margin-top: 48px;
    font-size: 1.2rem;
    font-weight: 800;
    color: #d94a31;
    letter-spacing: 2px;
    text-transform: uppercase;
    }

    .product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 32px;
    max-width: 1140px;
    margin: 48px auto 64px;
    padding: 0 24px;
    }

    .product-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 12px 36px rgba(217, 68, 40, 0.12);
    padding: 28px 24px;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-card:hover,
    .product-card:focus-within {
    transform: translateY(-14px);
    box-shadow: 0 22px 58px rgba(217, 68, 40, 0.24);
    outline: none;
    }

    .product-card img {
    max-width: 100%;
    max-height: 220px;
    object-fit: contain;
    border-radius: 14px;
    background: #fff;
    margin: 0 auto 22px;
    display: block;
    transition: transform 0.3s ease;
    }

    .product-card img:hover {
    transform: scale(1.06);
    }

    .product-card h3 {
    font-size: 1.15rem;
    color: #a33226;
    font-weight: 700;
    margin-bottom: 12px;
    }

    .product-card p {
    font-weight: 700;
    color: #61392f;
    font-size: 1.05rem;
    margin-bottom: 14px;
    }

    .product-code {
    font-size: 0.9rem;
    color: #b36155;
    margin-bottom: 14px;
    letter-spacing: 1.3px;
    font-weight: 600;
    }

    .order {
    background: linear-gradient(45deg, #ff7a62, #d83c24);
    color: #fff;
    border: none;
    border-radius: 20px;
    font-weight: 700;
    font-size: 1.1rem;
    padding: 14px 32px;
    cursor: pointer;
    transition: background 0.35s ease, transform 0.25s ease;
    box-shadow: 0 6px 18px rgba(255, 122, 98, 0.35);
    margin-bottom: 14px;
    }

    .order:hover,
    .order:focus {
    background: linear-gradient(45deg, #d83c24, #ff7a62);
    transform: scale(1.1);
    outline: none;
    }

    .remove-btn {
    background: #d83228;
    color: #fff;
    border: none;
    border-radius: 20px;
    font-weight: 700;
    font-size: 1rem;
    padding: 12px 28px;
    cursor: pointer;
    margin-top: 14px;
    transition: background 0.3s ease, transform 0.2s ease;
    box-shadow: 0 4px 14px rgba(216, 50, 40, 0.4);
    }

    .remove-btn:hover,
    .remove-btn:focus {
    background: #9f1f1a;
    transform: scale(1.07);
    outline: none;
    }

    footer {
    background-color: #3a1e1c;
    color: #ffd9d2;
    padding: 34px 20px;
    text-align: center;
    font-size: 14px;
    font-weight: 600;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
    margin-top: 70px;
    }

    footer a {
    color: #ffb4a5;
    margin: 0 10px;
    text-decoration: none;
    transition: color 0.3s ease;
    font-size: 1rem;
    }

    footer a:hover,
    footer a:focus {
    color: #fff0ec;
    outline: none;
    }

    .next-page {
    text-align: center;
    margin: 60px auto 0;
    }

    .next-page a {
    display: inline-block;
    background: linear-gradient(135deg, #f9543a, #e73125);
    color: #fff;
    padding: 16px 34px;
    border-radius: 22px;
    font-weight: 700;
    font-size: 1.15rem;
    text-decoration: none;
    transition: background 0.3s ease, transform 0.25s ease;
    box-shadow: 0 8px 25px rgba(249, 84, 58, 0.4);
    }

    .next-page a:hover,
    .next-page a:focus {
    background: linear-gradient(135deg, #e73125, #f9543a);
    transform: scale(1.12);
    outline: none;
    }

    @media (max-width: 768px) {
    nav a {
        display: block;
        margin: 14px 0;
    }

    .product-grid {
        padding: 0 16px 48px;
    }

    header h1 {
        font-size: 1.9rem;
    }
    }

        </style>
    </head>

    <body>
        <header>
            <h1>Maison D&G Store</h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="about.html">About</a>
                <a href="product.php">Product</a>
                <a href="contact.php">Contact</a>
                <a href="report.php">Report</a>
                <a href="cart.php">View Cart</a>
            </nav>
        </header>

        <div id="Zando">
            <h1>Zando</h1>
            <p>Discover the latest fashion trends and shop online at Zando.</p>
        </div>

        <?php if ($message) echo $message; ?>

        <h2>Products</h2>
        <div class="product-grid">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <?php if (!empty($row['image'])): ?>
                            <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                        <?php else: ?>
                            <img src="placeholder.png" alt="No Image">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($row['name']) ?></h3>
                        <span class="product-code">Code: <?= htmlspecialchars($row['product_code']) ?></span>
                        <p>$<?= number_format($row['price'], 2) ?></p>
                        <form action="" method="POST" style="display:flex; flex-direction: column; align-items:center;">
                            <input type="hidden" name="product_code" value="<?= htmlspecialchars($row['product_code']) ?>">

                            <a href="order.php?ProductCode=<?= urlencode($row['product_code']) ?>&ProName=<?= urlencode($row['name']) ?>&Price=<?= urlencode($row['price']) ?>" class="order">Order</a>

                            <button type="submit" name="add_to_cart" class="order">Add to cart</button>


                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="grid-column: 1 / -1; text-align:center;">No products found for Zando.</p>
            <?php endif; ?>
        </div>



        <footer>
            <p>&copy; 2025 DG Store. All rights reserved.</p>
            <p>
                <a href="https://web.facebook.com/lypha009" target="_blank"><i class="fab fa-facebook-f"></i> Facebook</a> |
                <a href="https://www.tiktok.com/@lyphaa20" target="_blank"><i class="fab fa-tiktok"></i> TikTok</a> |
                <a href="https://www.instagram.com/pha._.phaa" target="_blank"><i class="fab fa-instagram"></i> Instagram</a> |
                <a href="https://t.me/phaaphaaphaa" target="_blank"><i class="fab fa-telegram-plane"></i> Telegram</a>
            </p>
        </footer>
    </body>

    </html>

    <?php $conn->close(); ?>