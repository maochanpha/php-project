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

    // Check form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $message = trim($_POST['message']);

        if (!empty($name) && !empty($email) && !empty($message)) {
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $message);

            if ($stmt->execute()) {
                $success = "Your message has been sent successfully!";
            } else {
                $error = "Error: Unable to send your message.";
            }

            $stmt->close();
        } else {
            $error = "Please fill in all fields.";
        }
    }

    $conn->close();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Contact Us - Maison D&G Store</title>
        <style>
            * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: #fff8f4;
    color: #333;
}

header h1 {
    background-color: #ff6f43;
    width: 100%;
    padding: 20px;
    color: white;
    text-align: center;
    font-size: 2rem;
    font-weight: bold;
    letter-spacing: 1px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.contact-container {
    max-width: 600px;
    margin: 50px auto;
    background-color: #fff;
    padding: 35px 25px;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(255, 160, 122, 0.2);
    border: 1px solid #ffd6c4;
    animation: fadeIn 0.5s ease-in-out;
}

.contact-container h2 {
    text-align: center;
    margin-bottom: 25px;
    font-size: 2rem;
    color: #e86f51;
    font-weight: bold;
}

form {
    display: flex;
    flex-direction: column;
}

form label {
    margin-top: 16px;
    margin-bottom: 6px;
    font-weight: 600;
    color: #444;
    font-size: 1rem;
}

form input,
form textarea {
    padding: 12px;
    font-size: 1rem;
    border-radius: 10px;
    border: 1px solid #ccc;
    background-color: #fff;
    transition: all 0.3s ease;
}

form input:focus,
form textarea:focus {
    border-color: #ff9472;
    box-shadow: 0 0 0 3px rgba(255, 148, 114, 0.2);
    outline: none;
}

form button {
    margin-top: 20px;
    padding: 14px;
    font-size: 1rem;
    font-weight: bold;
    background-color: #ff9472;
    color: white;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

form button:hover {
    background-color: #ff6f43;
    transform: translateY(-2px);
}

form button:last-of-type {
    background-color: #555;
    margin-top: 12px;
}

form button:last-of-type:hover {
    background-color: #333;
}

.message {
    text-align: center;
    margin-bottom: 15px;
    font-weight: bold;
    font-size: 1rem;
}

.success {
    color: green;
}

.error {
    color: red;
}

footer {
    text-align: center;
    padding: 20px 15px;
    background-color: #111;
    color: white;
    margin-top: 50px;
    font-size: 0.9rem;
    letter-spacing: 0.5px;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 600px) {
    .contact-container {
        margin: 20px;
        padding: 20px;
    }

    header h1 {
        font-size: 1.5rem;
    }
}

        </style>
    </head>
    <body>
        <header>
            <h1>Contact Maison D&G Store</h1>
        </header>

        <div class="contact-container">
            <h2>Get in Touch</h2>

            <?php if ($success): ?>
                <p class="message success"><?= $success ?></p>
            <?php elseif ($error): ?>
                <p class="message error"><?= $error ?></p>
            <?php endif; ?>

            <form method="POST" action="">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>

                <label for="email">Your Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>

                <label for="message">Message</label>
                <textarea id="message" name="message" rows="6" placeholder="Your message here..." required></textarea>

                <button type="submit">Send Message</button>
                <button type="button" onclick="window.location.href = 'index.php'">Back Home</button>
            </form>
        </div>

        <footer>
            &copy; 2025 Maison D&G Store. All rights reserved.
        </footer>
    </body>
    </html>
