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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION["admin"] = $username;
            header("Location: admin_dashboard.php");
            exit;
        } else {
            $error = "❌ Incorrect password.";
        }
    } else {
        $error = "❌ Admin user not found.";
    }
}
?>

<head>
    <title>Amind Login</title>
    <style>

        body {
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
form {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    width: 320px;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.3);
}
h2 {
    margin-bottom: 25px;
    color: #ff5e3a;
    font-size: 1.8rem;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}
input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 12px;
    margin: 12px 0;
    border: 1px solid #ffb088;
    border-radius: 10px;
    background: #fff;
    font-size: 1rem;
    transition: 0.3s;
}
input:focus {
    border-color: #ff5e3a;
    box-shadow: 0 0 8px rgba(255, 94, 58, 0.3);
    outline: none;
}
button {
    background: linear-gradient(90deg, #ff5e3a, #ff8e53);
    color: white;
    border: none;
    padding: 12px 0;
    width: 100%;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}
button:hover {
    background: linear-gradient(90deg, #ff3d00, #ff7043);
    transform: scale(1.02);
}
a {
    display: block;
    margin-top: 18px;
    text-decoration: none;
    color: #444;
    font-size: 14px;
    transition: color 0.3s;
}
a:hover {
    color: #ff5e3a;
    text-decoration: underline;
}
p {
    margin-top: 12px;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

    </style>

</head>
<!-- Login Form -->
<form method="post">
    <h2>Admin Login</h2>
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
    <a href="index.php">Back Home</a>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</form>