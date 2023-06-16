 <!DOCTYPE html>
<html>
<head>
  <title>Edit Password</title>
  <link rel="stylesheet" href="editpass.css">
</head>
<body>
  <div class="container">
    <h1>Edit Password</h1>
    <form method="post" action="editpass.php">
      <div class="input">
        <label for="old_password">Old Password:</label>
        <input type="password" id="old_password" name="old_password" required>
      </div>
     
      <div class="input">
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
      </div>
      
      <div class="input">
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
      </div>
      
      <?php
      require "connection.php";
      session_start();

      
      if (!isset($_SESSION['email'])) {
        header('Location: http://localhost/gym-project2/login.php');
        exit;
      }

      if (isset($_POST['submit'])) {
        $oldPassword = $_POST['old_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        $email = $_SESSION['email'];

        
        $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        
        $hashedPassword = $row['password'];
        if (password_verify($oldPassword, $hashedPassword)) {
        
          if ($newPassword === $confirmPassword && $oldPassword !== $newPassword) {
        
            $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

          
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $newHashedPassword, $email);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
              echo "<p class='message success'>Password updated successfully  :)</p>";
            } else {
              echo "<p class='message error'>Failed to update password  :( </p>";
            }
          } else {
            echo "<p class='message error'>New password and confirm password do not match, or the new password is the same as the old password.</p>";
          }
        } else {
          echo "<p class='message error'>Incorrect old password   :( </p>";
        }
      }
      ?>
      
      <div class="actions">
        <div class="link"><a href="home.php">Return Home</a></div>
        <input type="submit" name="submit" value="Submit">
      </div>
    </form>
  </div>
</body>
</html>

