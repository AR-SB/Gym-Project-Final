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

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteUser'])) {
    $userId = $_POST['userId'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        // Redirect to the user list page after deleting a user
        header('Location: http://localhost/gym-project-php/admin.php');
        exit;
    } else {
        // Display an error message or log the error
        echo "Error deleting user: " . $stmt->error;
    }
}

// Fetch all users
$stmt = $conn->prepare("SELECT id, name, email FROM users");
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);

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
    <title>Delete User</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar">
    <div class="logo">Fitness</div>
        <form action="" method="POST">
        <button name="admin-panel">Admin Panel</button>
        </form>

    </nav>

    <div class="container">
        <div class="sidebar">
            <ul>
                <li><a href="AddUser.php">Add User</a></li>
                <li><a href="DeleteUser.php">Delete User</a></li>
                <li><a href="AddItem.php">Add Item</a></li>
                <li><a href="DeleteItem.php">Delete Item</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Delete User</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Display User Information -->
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['name']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="userId" value="<?php echo $user['id']; ?>">
                                    <button type="submit" name="deleteUser">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
        </div>
    </div>
    <!-- Sidebar -->

</body>

</html>
