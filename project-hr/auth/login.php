<?php
session_start();
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = md5($_POST['password']);

  $stmt = $pdo->prepare("SELECT * FROM users WHERE username=? AND password=?");
  $stmt->execute([$username, $password]);
  $user = $stmt->fetch();

  if ($user) {
    $_SESSION['user'] = $user;
    header("Location: ../index.php");
    exit;
  } else {
    $error = "Invalid username or password";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - HR System</title>
  <link rel="stylesheet" href="../public/css/style.css">
</head>
<body class="login-page">
  <div class="login-box">
    <h2>HR Management Login</h2>
    <?php if(!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
      <input type="text" name="username" placeholder="Username" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
