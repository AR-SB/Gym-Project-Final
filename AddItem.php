<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

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

// Handle form submission to add a new supply
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addSupply'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
// Check if a file was uploaded
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $imagePath = '/images/' . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . $imagePath);
} else {
    $imagePath = null; // No image uploaded, set to default value (null)
    echo "No file uploaded or upload failed. Image path set to null.";
}


    $stmt = $conn->prepare("INSERT INTO supplies (name, price, description, image_path) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $name, $price, $description, $imagePath);
    $stmt->execute();
    
    // Redirect to the supply list page after adding a supply
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
    <title>Add Item</title>
    <link rel="stylesheet" href="AddItem.css">
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
                <li><a href="AddItem.php">Add Item</a></li>
                <li><a href="DeleteItem.php">Delete Item</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Add Supply</h2>
            <form method="post" action="" enctype="multipart/form-data">

            <div class="line">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="line">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>

                <div class="line">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>
                </div>

                <div class="line">
                    <label for="image">Image:</label>
                    <input type="file" id="image" name="image">
                </div>

                    <button type="submit" id="add-button" name="addSupply">Add Supply</button>


            </form>
        </div>
    </div>

</body>

</html>
