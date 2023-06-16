<?php
session_start();

if (!isset($_SESSION['email'])) {
  header('Location: http://localhost/gym-project2/login.php');
  exit;
}

require "connection.php";

$stmt = $conn->prepare("SELECT name, email, profile_picture FROM users WHERE email = ?");
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$name = $user['name'];
$email = $user['email'];
$profile_picture = $user['profile_picture'];

$stmt = $conn->prepare("SELECT posts.title, posts.image, users.name AS user_name, users.profile_picture AS user_profile_picture FROM posts INNER JOIN users ON posts.user_id = users.id ORDER BY posts.timestamp DESC");
$stmt->execute();
$result = $stmt->get_result();

$posts = [];
while ($row = $result->fetch_assoc()) {
  $posts[] = $row;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {

  $stmt = $conn->prepare("DELETE FROM users WHERE email = ?");
  $stmt->bind_param("s", $_SESSION['email']);
  $stmt->execute();

  session_destroy();

  setcookie('login_preferences', '', time() - 3600, '/');

  header('Location: http://localhost/gym-project2/login.php');
  exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log-out'])) {
  session_destroy();
  setcookie('login_preferences', '', time() - 3600, '/');
  header('Location: http://localhost/gym-project2/login.php');
  exit;
}

  ?> 

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link rel="stylesheet" href="home.css">
  
</head>
<body>
  <header>

    <nav class="navbar">
      <div class="nav-left">
      <a href="#" onclick="event.preventDefault();" id="burger">&#9776;</a>
      <a href="#" onclick="event.preventDefault();" id="exit"> &#10005;</a>
      
     
      </div>
      <h2>Fitness</h2>
      <div class="nav-right">
        <a href="post.php" class="post-button">Post</a>
      </div>
      
    </nav>
  </header>
    
  <div class="sidebar">
    <div class="side-info">
      
        <?php if (!empty($profile_picture)) : ?>
          <img src="<?php echo $profile_picture; ?>">
        <?php endif; ?>
        <h2><?php echo $name; ?></h2>
        <h3><?php echo $email; ?></h3>
    </div>


    <div class="actions">
     <form action="" method="POST">
     <button type="submit" id="delete"  name="delete_account">Delete Account</button>
     <button type="submit" id="log-out" name="log-out">Log Out</button>
     <div id="setting"><a  href="editprofile.php">Edit Profile</a></div>
     </form>

  </div>
  </div>
  <div class="main-page">
    <?php foreach ($posts as $post) : ?>
      <div class="feed-box">
        <div class="profile">
          <?php if (!empty($post['user_profile_picture'])) : ?>
            <img class="prof-images" src="<?php echo $post['user_profile_picture']; ?>" >
          <?php endif; ?>
          <div class="prof-name"><h2><?php echo $post['user_name']; ?></h2></div>
        </div>
        <div class="expression"><h3><?php echo $post['title']; ?></h3></div>
        <div class="content-img">
          <?php if (!empty($post['image'])) : ?>
            <img class="images" src="<?php echo $post['image']; ?>">
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
  var burger = document.getElementById("burger");
  var exit = document.getElementById("exit");
  var sidebar = document.querySelector(".sidebar");

  burger.addEventListener("click", function() {
    sidebar.style.left = "0";
    burger.style.display = "none";
    exit.style.display = "block";
  });

  exit.addEventListener("click", function() {
    sidebar.style.left = "-250px";
    burger.style.display = "block";
    exit.style.display = "none";
  });
});

  </script>
</body>
</html>
