<?php
session_start();

require "connection.php";

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: http://localhost/gym-project-php/login.php');
    exit;
}

// Verify the user's email and password
$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if the user has the admin role
if (!$user || $user['role'] !== 'admin') {
    // Redirect to a different page or show an error message
    echo "You don't have permission to access this page.";
    exit;
}

// Handle form submission to add a new user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addUser'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO users (name, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $email);
    $stmt->execute();
    // Redirect to the user list page after adding a user
    header('Location: http://localhost/gym-project-php/admin.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin-panel'])) {
    header('Location: http://localhost/gym-project-php/admin.php');
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>
 <!-- Navbar -->
 <div class="navbar">
        <div class="logo">Fitness</div>
        <form action="" method="POST">
        <button name="admin-panel">Admin Panel</button>
        </form>
  
</div>

    <div class="container">
        <div class="sidebar">
            <ul>
                <li><a href="AddUser.php">Add User</a></li>
                <li><a href="DeleteUser.php">Delete User</a></li>
                <li><a href="#">Add Item</a></li>
                <li><a href="DeleteItem.php">Delete Item</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Add User</h2>
            <form method="post" action="">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <button type="submit" name="addUser">Add User</button>
            </form>
        </div>
    </div>
    <!-- Sidebar -->

</body>

</html>
