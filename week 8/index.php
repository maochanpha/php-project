<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        div {
            width: 400px;
            margin: 0 auto;
            padding: 25px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            text-align: center;
        }

        h1,
        h2 {
            text-align: center;
            color: #333;
            font-size: 20px;
            font-family: Arial, sans-serif;
        }

        input,
        button {
            padding: 15px;
            width: 50%;
            margin: 10px auto;
            border-radius: 5px;
            border: #ccc;
            cursor: pointer;
            color: skyblue;
        }

        ::placeholder {
            color: #888;
            text-align: center;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div>
        <h1>Form Login</h1>
        <h2>Enter Username and Password</h2>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Enter username" required> <br>
            <input type="password" name="password" placeholder="Enter password" required> <br>
            <button value="submit">Submit</button>
        </form>
    </div>
</body>

</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "login";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connect Failed: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];


        $sql = "INSERT INTO login (username, password) VALUES ('$username', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "New user inserted!";
        } else {
            echo "Error: " . $conn->error;
        }
        $conn->close();
    }

    

?>
