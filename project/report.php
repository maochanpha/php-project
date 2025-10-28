<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "product"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = $error = "";
$order_id = "";
$issue_type = "";
$details = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = trim($_POST['order-id']);
    $issue_type = trim($_POST['issue-type']);
    $details = trim($_POST['details']);

    if (empty($issue_type)) {
        $error = "Please select an issue type.";
    } elseif (empty($details)) {
        $error = "Please provide details about the issue.";
    } else {
        $stmt = $conn->prepare("INSERT INTO issue_reports (order_id, issue_type, details, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $order_id, $issue_type, $details);

        if ($stmt->execute()) {
            $success = "Thank you! Your issue report has been submitted.";
            $order_id = $issue_type = $details = "";
        } else {
            $error = "Error saving your report. Please try again.";
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
    <title>Report an Issue - DG Store</title>
    <style>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background-color: #fff6f1; 
    color: #444;
    line-height: 1.6;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

header {
    background: linear-gradient(90deg, #ff9c72, #ff7248);
    color: #fff;
    padding: 28px 20px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(255, 156, 114, 0.4);
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
    user-select: none;
}

header h1 {
    font-size: 2.6rem;
    font-weight: 900;
    letter-spacing: 2px;
    transition: transform 0.3s ease;
    cursor: default;
}

header h1:hover {
    transform: scale(1.08) rotate(-2deg);
}

.report-container {
    background: #fff;
    max-width: 620px;
    width: 90%;
    margin: 50px auto 60px;
    padding: 36px 48px;
    border-radius: 18px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease;
}

.report-container:hover,
.report-container:focus-within {
    box-shadow: 0 18px 40px rgba(0, 0, 0, 0.15);
}

.report-container h2 {
    margin-bottom: 30px;
    font-weight: 700;
    font-size: 2rem;
    color: #ff7043;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 1.4px;
}

form label {
    display: block;
    margin-top: 24px;
    margin-bottom: 10px;
    font-weight: 600;
    color: #666;
    font-size: 1.1rem;
}

input[type="text"],
select,
textarea {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid #ffbfa3;
    border-radius: 12px;
    font-size: 1.05rem;
    font-family: inherit;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
    background-color: #fffaf6;
    color: #444;
}

input[type="text"]:focus,
select:focus,
textarea:focus {
    border-color: #ff7043;
    box-shadow: 0 0 10px rgba(255, 112, 67, 0.4);
    outline: none;
}

textarea {
    resize: vertical;
    min-height: 140px;
}

button[type="submit"],
button[type="button"],
button {
    background: linear-gradient(135deg, #ff7a48, #ff512f);
    color: #fff;
    padding: 16px 30px;
    border: none;
    border-radius: 20px;
    font-size: 1.15rem;
    font-weight: 700;
    cursor: pointer;
    margin-top: 36px;
    transition: background 0.35s ease, transform 0.25s ease;
    box-shadow: 0 6px 20px rgba(255, 82, 47, 0.4);
    user-select: none;
}

button[type="submit"]:hover,
button[type="submit"]:focus {
    background: linear-gradient(135deg, #ff512f, #ff7a48);
    transform: scale(1.08);
    outline: none;
}

button[type="button"] {
    background: #f0f0f0;
    color: #666;
    box-shadow: none;
    margin-left: 18px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

button[type="button"]:hover,
button[type="button"]:focus {
    background-color: #ddd;
    color: #444;
    outline: none;
    transform: none;
}

@media (max-width: 480px) {
    .report-container {
        padding: 28px 24px;
    }

    button[type="submit"],
    button[type="button"] {
        width: 100%;
        margin: 16px 0 0 0 !important;
        display: block;
    }
}

footer {
    margin-top: auto;
    background: linear-gradient(90deg, #ff9c72, #ff7248);
    color: white;
    text-align: center;
    padding: 18px 12px;
    font-size: 1rem;
    font-weight: 600;
    box-shadow: 0 -5px 18px rgba(255, 156, 114, 0.45);
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
    user-select: none;
}

footer a {
    color: #ffd3bf;
    margin: 0 12px;
    text-decoration: none;
    transition: color 0.3s ease;
}

footer a:hover,
footer a:focus {
    color: #fff2eb;
    outline: none;
    text-decoration: underline;
}

    </style>
</head>
<body>
    <header>
        <h1>Report an Issue</h1>
</header>

<div class="report-container">
    <h2>Help Us Fix the Problem</h2>

    <?php if ($success): ?>
        <p style="color:green; font-weight:600; text-align:center; margin-bottom:20px;"><?= htmlspecialchars($success) ?></p>
    <?php elseif ($error): ?>
        <p style="color:red; font-weight:600; text-align:center; margin-bottom:20px;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="order-id">Order ID (optional)</label>
        <input type="text" id="order-id" name="order-id" placeholder="Enter order ID if applicable" value="<?= htmlspecialchars($order_id) ?>">

        <label for="issue-type">Issue Type</label>
        <select id="issue-type" name="issue-type" required>
            <option value="" <?= $issue_type == "" ? 'selected' : '' ?>>Select an issue</option>
            <option value="delivery" <?= $issue_type == "delivery" ? 'selected' : '' ?>>Delivery Problem</option>
            <option value="product" <?= $issue_type == "product" ? 'selected' : '' ?>>Product Issue</option>
            <option value="payment" <?= $issue_type == "payment" ? 'selected' : '' ?>>Payment Problem</option>
            <option value="other" <?= $issue_type == "other" ? 'selected' : '' ?>>Other</option>
        </select>

        <label for="details">Details</label>
        <textarea id="details" name="details" rows="6" placeholder="Describe the issue in detail..." required><?= htmlspecialchars($details) ?></textarea>

        <button type="submit">Submit Report</button>
        <button type="button" onclick="window.location.href='index.php'">Back Home</button>
    </form>
</div>

<footer>
    &copy; 2025 Maison D&G Store. All rights reserved.
</footer>
</body>
</html>
</html>
