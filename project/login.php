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
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user'] = $row['name'];
            header("Location: index.php");
            exit;
        } else {
            $message = "Invalid password!";
        }
    } else {
        $message = "User not found!";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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

        .login-container {
            background: white;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #ff6b6b;
        }

        .login-container form {
            display: flex;
            flex-direction: column;
        }

        .login-container label {
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .login-container input[type="email"],
        .login-container input[type="password"] {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        .login-container input[type="submit"] {
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
            margin: 10px;
        }

        .login-container input[type="submit"]:hover {
            background: #e74c3c;
        }

        .login-container p {
            margin-top: 16px;
            font-size: 0.9rem;
        }

        .login-container a {
            color: #ff6b6b;
            text-decoration: none;
            font-weight: bold;
        }

        .login-container a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <?php if ($message) echo "<p class='error-message'>$message</p>"; ?>
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>


        <input type="submit" value="Login">
    </form>
    <p>No account? <a href="register.php">Register</a></p>
    <a href="index.php">Back to Dashboard</a>
</div>

</body>
</html>
