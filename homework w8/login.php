<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff2ed;
            padding: 60px 20px;
            text-align: center;
            color: #5a3e2b;
        }

        form {
            background: #ffffff;
            border-radius: 15px;
            padding: 40px 35px;
            width: 320px;
            margin: 0 auto;
            box-shadow: 0 8px 20px rgba(211, 115, 85, 0.25);
            transition: box-shadow 0.3s ease;
        }

        form:hover {
            box-shadow: 0 10px 30px rgba(211, 115, 85, 0.4);
        }

        input[type="email"],
        input[type="password"] {
            width: 60%;
            padding: 14px 16px;
            margin: 15px 0;
            border-radius: 12px;
            border: 2px solid #f5b49f;
            font-size: 16px;
            font-weight: 500;
            background-color: #fff7f3;
            color: #5a3e2b;
            transition: border-color 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #d75c2f;
            background-color: #ffe9de;
        }

        input[type="submit"] {
            background-color: #d75c2f;
            color: white;
            border: none;
            padding: 14px 24px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(215, 92, 47, 0.4);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #b04a21;
            box-shadow: 0 7px 20px rgba(176, 74, 33, 0.6);
        }

        h1 {
            color: #a74a26;
            font-weight: 700;
            margin-bottom: 35px;
            font-size: 2.5rem;
            text-shadow: 1px 1px 3px #f7d3c5;
        }

        p {
            color: #7d4c37;
            font-weight: 500;
            margin-top: 25px;
            font-size: 1rem;
        }

        a {
            color: #d75c2f;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            text-decoration: underline;
            color: #b04a21;
        }
    </style>
</head>

<body>
    <h1>Login</h1>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="submit" value="Login">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </form>
</body>

</html>

<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if ($pass === $row['password']) {  // direct compare without hash
            $_SESSION['user'] = $row;
            header("Location: dashboard.php");
            exit;
        } else {
            echo "<p style='color:red;'>Invalid password!</p>";
        }
    } else {
        echo "<p style='color:red;'>User not found!</p>";
    }
}
?>