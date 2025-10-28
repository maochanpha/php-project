<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff6f1;
            padding: 60px 20px;
            text-align: center;
            color: #6b3e2b;
        }

        h1 {
            color: #d35400;
            font-weight: 700;
            font-size: 2.6rem;
            margin-bottom: 40px;
            text-shadow: 1px 1px 4px #f8d6c3;
        }

        form {
            background: #fff;
            max-width: 360px;
            margin: 0 auto;
            padding: 35px 30px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(211, 115, 85, 0.25);
            transition: box-shadow 0.3s ease;
        }

        form:hover {
            box-shadow: 0 12px 40px rgba(211, 115, 85, 0.4);
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"] {
            width: 100%;
            padding: 14px 16px;
            margin-bottom: 22px;
            border-radius: 12px;
            border: 2px solid #f2b8a0;
            font-size: 16px;
            font-weight: 500;
            background-color: #fff8f3;
            color: #6b3e2b;
            transition: border-color 0.3s ease, background-color 0.3s ease;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="file"]:focus {
            outline: none;
            border-color: #d35400;
            background-color: #ffe9d6;
        }

        input[type="submit"] {
            background-color: #d35400;
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 14px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 700;
            box-shadow: 0 6px 20px rgba(211, 84, 47, 0.4);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #a63f00;
            box-shadow: 0 8px 25px rgba(166, 63, 0, 0.6);
        }

        p {
            color: #7d4c37;
            font-weight: 600;
            margin-top: 30px;
            font-size: 1rem;
        }

        a {
            color: #d35400;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            text-decoration: underline;
            color: #a63f00;
        }
    </style>
</head>

<body>
    <h1>Register here!</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Full Name" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="text" name="department" placeholder="Department" required><br>
        <input type="file" name="image" required><br>
        <input type="submit" value="Register">
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>
</body>

</html>

<?php
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $img = $_FILES['image']['name'];
    $department = $_POST['department'];

    // Profile picture
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    $sql = "INSERT INTO users (name, email, password, department, image) VALUES ('$name', '$email', '$password', '$department', '$img')";
    if ($conn->query($sql)) {
        echo "Registered Successfully!";
        header("Location:login.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>