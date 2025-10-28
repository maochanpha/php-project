<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e7f0fd;
            padding: 60px 20px;
            text-align: center;
            color: #2a3d66;
        }

        .profile-box {
            background: #ffffff;
            border-radius: 18px;
            padding: 40px 35px;
            width: 380px;
            margin: 0 auto;
            box-shadow: 0 8px 25px rgba(60, 94, 171, 0.15);
            transition: box-shadow 0.3s ease;
        }

        .profile-box:hover {
            box-shadow: 0 12px 40px rgba(60, 94, 171, 0.3);
        }

        .profile-box img {
            border-radius: 50%;
            width: 110px;
            height: 110px;
            object-fit: cover;
            border: 4px solid #a8c0ff;
            margin-bottom: 20px;
        }

        .profile-box h2 {
            margin-bottom: 8px;
            font-weight: 700;
            font-size: 1.9rem;
            color: #1f2a56;
        }

        .profile-box p {
            margin: 6px 0;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .button-group {
            margin-top: 30px;
        }

        .button-group a {
            display: inline-block;
            background-color: #4f75ff;
            color: #fff;
            padding: 12px 22px;
            margin: 8px 6px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 5px 15px rgba(79, 117, 255, 0.4);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .button-group a:hover {
            background-color: #3751d1;
            box-shadow: 0 7px 22px rgba(55, 81, 209, 0.6);
        }
    </style>
</head>

<body>

    <div class="profile-box">
        <h2>Welcome, Prof. <?= $user['name'] ?></h2>
        <img src="uploads/<?= $user['image'] ?>" alt="Profile Image"><br><br>

        <p><strong>Email:</strong> <?= $user['email'] ?></p>
        <p><strong>Department:</strong> <?= $user['department'] ?></p>

        <div class="button-group">
            <a href="edit_profile.php">Edit Profile</a>
            <a href="my_courses.php">My Courses</a>
            <a href="student_list.php">Student List</a>
            <a href="grade.php">Grade</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

</body>

</html>