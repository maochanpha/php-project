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
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['number'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $message = "Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $pass);
        if ($stmt->execute()) {
            $message = "✅ Registration successful! You can now <a href='login.php'>Login</a>.";
        } else {
            $message = "❌ Error during registration!";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f7f1ee;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-container {
            background: white;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .register-container h2 {
            margin-bottom: 20px;
            color: #ff6b6b;
        }

        .register-container form {
            display: flex;
            flex-direction: column;
        }

        .register-container label {
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .register-container input[type="text"],
        .register-container input[type="email"],
        .register-container input[type="password"],
        .register-container input[type="number"] {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        .register-container input[type="submit"] {
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        .register-container input[type="submit"]:hover {
            background: #e74c3c;
        }

        .register-container p {
            margin-top: 16px;
            font-size: 0.9rem;
        }

        .register-container a {
            color: #ff6b6b;
            text-decoration: none;
            font-weight: bold;
        }

        .register-container a:hover {
            text-decoration: underline;
        }

        .message {
            margin-bottom: 16px;
            color: #e74c3c;
        }

        .message a {
            color: #2ecc71;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Register</h2>
    <?php if ($message) echo "<p class='message'>$message</p>"; ?>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Phone:</label>
        <input type="number" name="number" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <input type="submit" value="Register">
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
</div>

</body>
</html>
