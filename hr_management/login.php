<?php
session_start();
include 'db.php'; // 👈 must be before using $conn

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$user' AND password=MD5('$pass')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['user'] = $user;
        header('Location: index.php');
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - HR System</title>
  <style>
    body.login-body {
  font-family: 'Segoe UI', Arial, sans-serif;
  background-color: lightblue;
  height: 100vh;
  margin: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.login-box {
  background: #ffffff;
  padding: 40px 50px;
  border-radius: 16px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  width: 100%;
  max-width: 380px;
  text-align: center;
  animation: fadeIn 0.6s ease-in-out;
}

.login-box h2 {
  margin-bottom: 25px;
  color: #1e293b;
  font-size: 1.8rem;
  letter-spacing: 0.5px;
}

.login-box input[type="text"],
.login-box input[type="password"] {
  width: 80%;
  padding: 12px 15px;
  margin-bottom: 18px;
  border: 1px solid #cbd5e1;
  border-radius: 10px;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.login-box input:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 5px rgba(59, 130, 246, 0.4);
  outline: none;
}

.login-box button {
  width: 100%;
  padding: 12px;
  background-color: #3b82f6;
  color: white;
  border: none;
  border-radius: 10px;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.3s ease;
}

.login-box button:hover {
  background-color: #2563eb;
}

.error {
  color: #ef4444;
  background: #fee2e2;
  border: 1px solid #fca5a5;
  border-radius: 8px;
  padding: 10px;
  margin-bottom: 15px;
  font-size: 0.95rem;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 480px) {
  .login-box {
    padding: 30px 25px;
  }
}

  </style>
</head>
<body class="login-body">
  <div class="login-box">
    <h2>HR Management Login</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
