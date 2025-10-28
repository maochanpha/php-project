    <?php
    include 'db.php'; // connect to database

    // Get form data
    $name = $_POST['name'];
    $subject1 = $_POST['subject1'];
    $subject2 = $_POST['subject2'];
    $subject3 = $_POST['subject3'];

    // Calculate total
    $total = $subject1 + $subject2 + $subject3;

    // Check for duplicate
    $check = $conn->prepare("SELECT id FROM students WHERE name = ?");
    $check->bind_param("s", $name);
    $check->execute();
    $check->store_result();

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Evaluation Result</title>
        <link rel="stylesheet" href="style.css">
        <style>
            .success-msg {
                color: #155724; 
                background: #d4edda;
                border: 1px solid #c3e6cb;
                padding: 12px;
                border-radius: 5px;
                margin: 60px auto 20px auto;
                max-width: 400px;
                text-align: center;
                font-size: 18px;
                font-family: 'Arial', sans-serif;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .error-msg {
                color: #721c24;
                background: #f8d7da;
                border: 1px solid #f5c6cb;
                padding: 12px;
                border-radius: 5px;
                margin: 60px auto 20px auto;
                max-width: 400px;
                text-align: center;
                font-size: 18px;
                font-family: 'Arial', sans-serif;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .result-link {
                text-align: center;
                margin-top: 100px;
                font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            }

            .result-link a {
                color: #fff;
                background: #007bff;
                padding: 8px 18px;
                border-radius: 4px;
                text-decoration: none;
                font-weight: bold;
                transition: background 0.2s;
            }

            .result-link a:hover {
                background: #0056b3;
            }
        </style>
    </head>

    <body>
        <?php
        if ($check->num_rows > 0) {
            // Update existing student
            $update = $conn->prepare("UPDATE students SET subject1=?, subject2=?, subject3=?, total=? WHERE name=?");
            $update->bind_param("iiiis", $subject1, $subject2, $subject3, $total, $name);
            if ($update->execute()) {
                echo "<div class='success-msg'>✅ Student score updated successfully!</div>";
                echo "<div class='result-link'><a href='result.php'>View Results</a></div>";
            } else {
                echo "<div class='error-msg'>❌ Error updating score: " . $conn->error . "</div>";
            }
            $update->close();
        } else {
            // Insert new student
            $sql = $conn->prepare("INSERT INTO students (name, subject1, subject2, subject3, total) VALUES (?, ?, ?, ?, ?)");
            $sql->bind_param("siiii", $name, $subject1, $subject2, $subject3, $total);
            if ($sql->execute()) {
                echo "<div class='success-msg'>✅ Evaluation submitted successfully!</div>";
                echo "<div class='result-link'><a href='result.php'>View Results</a></div>";
            } else {
                echo "<div class='error-msg'>❌ Error: " . $conn->error . "</div>";
            }
            $sql->close();
        }
        $check->close();
        $conn->close();
        ?>
    </body>

    </html>