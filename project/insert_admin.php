<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "product"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = "admin";
$plainPassword = "admin123";

// Check if admin already exists
$check = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "⚠️ Admin user '<strong>$username</strong>' already exists.";
} else {
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO admin_users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashedPassword);

    if ($stmt->execute()) {
        echo "✅ Admin user '<strong>$username</strong>' created successfully.";
    } else {
        echo "❌ Error: " . $stmt->error;
    }
}

$conn->close();
