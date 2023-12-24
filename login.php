<?php
require "connection.php";

session_start();

$error = "";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['email'] = $email;

            // Check if the user is an admin
            if ($user['role'] === 'admin') {
                $_SESSION['admin'] = true;
                header("Location: http://localhost/gym-project-php/admin.php");
                die;
            }

            if (isset($_POST['remember_me'])) {
                $cookie_name = "login_preferences";
                $cookie_value = "email=" . $email;
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // Cookie expires in 30 days
            }

            header("Location: http://localhost/gym-project-php/home.php");
            die;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Invalid email.";
    }
}

if (isset($_COOKIE['login_preferences'])) {
    header("Location: http://localhost/gym-project-php/home.php");
    die;
}
?>


<!DOCTYPE html>
<html>
  <head>
    <title>LOGIN</title>
    <link rel="stylesheet" href="login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
  </head>
  <body>
    <div class="background"></div>
    <div class="container">
      <div class="item">
        <h2 class="logo"><ion-icon name="logo-xing"></ion-icon>Fitness World</h2>
        <div class="text-item">
          <h2>Welcome!<br><span>
            To Our World
          </span></h2>
          <p>Be ready to awaken the fighter inside you.</p>
          <div class="social-icon">
            <a href="#"><ion-icon name="logo-facebook"></ion-icon></a>
            <a href="#"><ion-icon name="logo-instagram"></ion-icon></a>
            <a href="#"><ion-icon name="logo-twitter"></ion-icon></a>
            <a href="#"><ion-icon name="logo-tiktok"></ion-icon></a>
          </div>
        </div>
      </div>
      <div class="login-section">
        <div class="form-box login">
          <form action="" method="POST">
            <h2>Sign in</h2>
            <div class="input-box">
              <span class="icon"><ion-icon name="person-outline"></ion-icon></span>
              <input type="email" name="email" id="email" required>
              <label for="email">Email</label>
            </div>
            <div class="input-box">
              <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
              <input type="password" name="password" id="password" required>
              <label for="password">Password</label>
            </div>
              <input type="checkbox" name="remember_me" id="remember_me">
              <label for="remember_me">Remember Me</label>

            <input class="btn" type="submit" name="login" value="Login">
            <div class="create-account">
              <p>Create a New Account: <a href="signup.php" class="register-link">Sign up</a></p>
              <?php if (!empty($error)) : ?>
              <p id="error"><?php echo $error; ?></p>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="/login/login.js"></script>
  </body>
</html>
