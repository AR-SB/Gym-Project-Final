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

// Handle search query
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $searchTerm = $_POST['searchTerm'];
    $stmt = $conn->prepare("SELECT name, email FROM users WHERE name LIKE ?");
    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // If no search query, fetch all users
    $stmt = $conn->prepare("SELECT name, email FROM users");
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log-out'])) {
  session_destroy();
  setcookie('login_preferences', '', time() - 3600, '/');
  header('Location: http://localhost/gym-project-php/login.php');
  exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
  <link rel="stylesheet" href="admin.css">
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar">
    <div class="logo">Fitness</div>
    <div class="website-name">Admin Panel</div>
    <form method="POST" action="">
        <input type="text" class="search-input" placeholder="Search" name="searchTerm">
        <button type="submit" name="search" id="search">Search</button>
        <button type="submit" name="log-out">Logout</button>
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
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
          <!-- Display User Information -->
          <?php foreach ($users as $user) : ?>
            <tr>
              <td><?php echo $user['name']; ?></td>
              <td><?php echo $user['email']; ?></td>
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
