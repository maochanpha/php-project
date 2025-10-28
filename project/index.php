<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maison D&G Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
:root {
    --bg: #fff4f0;
    --text: #222;
    --accent: #ff8a65;
    --accent-dark: #e85c3c;
    --gradient: linear-gradient(135deg, #ff9a76, #ff6b6b);
    --white: #fff;
    --shadow: 0 8px 20px rgba(255, 138, 101, 0.25);
    --radius: 20px;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', sans-serif;
    background: var(--bg);
    color: var(--text);
    line-height: 1.7;
    scroll-behavior: smooth;
}

/* HEADER */
header {
    background: var(--gradient);
    color: var(--white);
    padding: 30px 20px;
    text-align: center;
    position: sticky;
    top: 0;
    z-index: 1000;
    border-bottom-left-radius: var(--radius);
    border-bottom-right-radius: var(--radius);
    box-shadow: var(--shadow);
}

header h1 {
    font-size: 2.6rem;
    letter-spacing: 1px;
    transition: transform 0.3s ease;
}

header h1:hover {
    transform: scale(1.05) rotate(-1deg);
}

/* NAVIGATION */
nav {
    margin-top: 15px;
}

nav a {
    background: var(--white);
    color: var(--accent-dark);
    padding: 10px 18px;
    margin: 0 10px;
    border-radius: var(--radius);
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: var(--shadow);
}

nav a:hover {
    background: var(--accent-dark);
    color: white;
}

/* FORM */
form {
    background: var(--white);
    max-width: 850px;
    margin: 60px auto;
    padding: 50px 40px;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    text-align: center;
}

form img {
    width: 220px;
    border-radius: var(--radius);
    margin-bottom: 20px;
    transition: transform 0.3s ease;
}

form img:hover {
    transform: scale(1.05);
}

form p {
    font-size: 1.1rem;
    color: var(--text);
}

.logo-row {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px;
    margin: 50px auto;
    padding: 10px;
}

.logo-row img {
    height: 130px;
    border-radius: var(--radius);
    transition: transform 0.4s ease, box-shadow 0.3s ease;
    box-shadow: var(--shadow);
}

.logo-row img:hover {
    transform: scale(1.07) rotate(2deg);
}


.order {
    display: inline-block;
    margin: 30px 100px;
    padding: 14px 32px;
    font-size: 1rem;
    font-weight: bold;
    color: white;
    background: var(--gradient);
    border-radius: var(--radius);
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 0 14px rgba(255, 107, 107, 0.3);
}

.order:hover {
    background: linear-gradient(135deg, #ff6b6b, #e74c3c);
    transform: scale(1.05);
}


footer {
    background: #222;
    color: #eee;
    text-align: center;
    padding: 30px 20px;
    font-size: 15px;
    margin-top: 80px;
    border-top-left-radius: var(--radius);
    border-top-right-radius: var(--radius);
}

footer a {
    color: #ffa388;
    margin: 0 10px;
    text-decoration: none;
    transition: color 0.3s;
}

footer a:hover {
    color: white;
}

.right {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    margin-top: 25px;
    margin-right: 30px;
    font-size: 1.1rem;
    font-weight: 600;
    gap: 10px;
}

.right a {
    color: #d2691e; 
    text-decoration: none;
    padding: 8px 16px;
    border: 2px solid #d2691e;
    border-radius: 12px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.right a:hover {
    background-color: #d2691e;
    color: white;
    box-shadow: 0 4px 12px rgba(210, 105, 30, 0.5);
    cursor: pointer;
}

@media (max-width: 768px) {
    header h1 {
        font-size: 2rem;
    }

    .order {
        padding: 12px 24px;
        font-size: 0.95rem;
    }

    form {
        padding: 30px 20px;
    }

    .logo-row {
        gap: 20px;
    }

    nav a {
        margin: 8px 6px;
        display: inline-block;
    }
}


    </style>
</head>
<body>
    <header>
        <h1>Maison D&G Store</h1>
        <nav>
            <a href="#top">Home</a>
            <a href="about.html">About</a>
            <a href="product.php">Product</a>
            <a href="contact.php">Contact</a>
            <a href="report.php">Report</a>
            
            <div class="right">
                <a href="admin_login.php">Admin</a>
            </div>
        </nav>
    </header>

    <form id="top">
        <img src="logo/logo.png" alt="logo" align="left">
        <p>Welcome to Maison D&G Store. Go to place for stylish products and accessories!</p>
    </form>

    <div class="logo-row">
        <a href="zando.php"><img src="logo/zando.png" alt="Zando"></a>
    </div>

    <?php if(isset($_SESSION['user'])): ?>
        <a href="logout.php" class="order">Logout</a>
    <?php else: ?>
        <a href="login.php" class="order">Login</a>
    <?php endif; ?>

    <footer>
        <p>&copy; 2025 Maison D&G Store. All rights reserved.</p>
        <p>
            <a href="https://web.facebook.com/lypha009" target="_blank"><i class="fab fa-facebook-f"></i> Facebook</a> |
            <a href="https://www.tiktok.com/@lyphaa20" target="_blank"><i class="fab fa-tiktok"></i> TikTok</a> |
            <a href="https://www.instagram.com/pha._.phaa" target="_blank"><i class="fab fa-instagram"></i> Instagram</a> |
            <a href="https://t.me/phaaphaaphaa" target="_blank"><i class="fab fa-telegram-plane"></i> Telegram</a>
        </p>
    </footer>
</body>
</html>
