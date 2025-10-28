<?php
session_start();
include 'db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user']; // get current session user
$userId = $user['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];

    $image = $user['image']; // default image
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($tmp, "uploads/" . $image);
    }

    $sql = "UPDATE users SET name='$name', email='$email', department='$department', image='$image' WHERE id=$userId";

    if ($conn->query($sql)) {
        // Update session data
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['department'] = $department;
        $_SESSION['user']['image'] = $image;

        echo "Profile updated successfully. <a href='dashboard.php'>Go back to dashboard</a>";
        header("Location: dashboard.php");
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Profile</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e6f0fa;
            padding: 50px 20px;
            margin: 0;
            color: #2c3e70;
        }

        h2 {
            text-align: center;
            margin-bottom: 40px;
            font-weight: 700;
            font-size: 2.4rem;
            color: #1a2a57;
        }

        .profile-container {
            max-width: 480px;
            margin: 0 auto;
            background: #ffffff;
            padding: 35px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(100, 130, 230, 0.15);
            transition: box-shadow 0.3s ease;
        }

        .profile-container:hover {
            box-shadow: 0 15px 45px rgba(100, 130, 230, 0.3);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #405a9e;
        }

        input[type="text"],
        input[type="email"],
        input[type="file"] {
            width: 100%;
            padding: 14px 16px;
            margin-bottom: 25px;
            border-radius: 14px;
            border: 2px solid #aab8e7;
            font-size: 16px;
            font-weight: 500;
            background-color: #f9fbff;
            color: #2c3e70;
            box-sizing: border-box;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="file"]:focus {
            outline: none;
            border-color: #4466ee;
            background-color: #e3eaff;
        }

        .profile-pic-preview {
            text-align: center;
            margin-bottom: 25px;
        }

        .profile-pic-preview img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #aab8e7;
            box-shadow: 0 4px 15px rgba(100, 130, 230, 0.3);
        }

        input[type="submit"] {
            background-color: #4466ee;
            color: white;
            border: none;
            padding: 16px 0;
            border-radius: 20px;
            cursor: pointer;
            width: 100%;
            font-weight: 700;
            font-size: 18px;
            box-shadow: 0 6px 20px rgba(68, 102, 238, 0.5);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #2a3ebb;
            box-shadow: 0 8px 30px rgba(42, 62, 187, 0.7);
        }
    </style>

</head>

<body>
    <h2>Edit Profile</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?= $user['name'] ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?= $user['email'] ?>" required><br><br>

        <label>Department:</label><br>
        <input type="text" name="department" value="<?= $user['department'] ?>" required><br><br>

        <label>Profile Image:</label><br>
        <input type="file" name="image"><br>
        <img src="uploads/<?= $user['image'] ?>" width="100" alt="Profile Image"><br><br>

        <input type="submit" value="Update Profile">

    </form>
</body>

</html>