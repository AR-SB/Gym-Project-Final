<?php
session_start();

if (!isset($_SESSION['email'])) {
  header('Location: http://localhost/gym-project-php/login.php');
  exit;
}

require "connection.php";

$stmt = $conn->prepare("SELECT id, name, profile_picture FROM users WHERE email = ?");
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$name = $user['name'];
$profile_picture = $user['profile_picture'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $thought = $_POST['thought'];

  $image = $_FILES['image']['name'];
  $image_tmp = $_FILES['image']['tmp_name'];
  $image_dir = '/uploads' . $image;

  move_uploaded_file($image_tmp, $image_dir);

  $stmt = $conn->prepare("INSERT INTO posts (user_id, title,images) VALUES (?, ?, ?)");
  $stmt->bind_param("iss", $user['id'], $thought, $image_dir);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    echo "<p class='message success'>Post created successfully  :)</p>";
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
      die('File upload failed with error code ' . $_FILES['image']['error']);
  }
  
    sleep(3);
    header('Location: http://localhost/gym-project-php/home.php');
    exit;
  } else {
    echo "<p class='message error'>Failed to create post  :( </p>";
  }

  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POST</title>
  <link rel="stylesheet" href="post.css">
</head>
<body>
      <div class="post-preview">
        <div class="profile">
        <?php if (!empty($profile_picture)) : ?>
          <img class="prof-images" src="<?php echo $profile_picture; ?>">
        <?php endif; ?>
        <div class="prof-name">
          <h2><?php echo $name; ?></h2>
        </div>
      </div>
        <div class="expression">
          <h3 id="expression-h3"></h3>
        </div>
        <div class="content-img">
        <div class="img"></div>
        </div>
      </div>
      <div class="create-post">
        <form action="" method="POST" enctype="multipart/form-data">
          <div class="input">
            <label id="thought" for="thought">Share A Thought!</label>
            <input type="text" name="thought" id="thought-input" maxlength="30" required oninput="updateExpression()">
          </div>
          <div class="actions">
            <button >Cancel</button>
            <label for="image" class="upload-btn">Share A Photo</label>
            <input type="file" name="image" id="image" accept="image/*" class="file-input" required onchange="displayImage(event)">
            <input type="submit" value="Share" id="share">
          </div>
        </form>
      </div>
      
<script>
  const imagePreview = document.querySelector(".img");
  const fileInput = document.querySelector(".file-input");

  fileInput.addEventListener("change", function () {
    const file = this.files[0];

    if (file) {
      const reader = new FileReader();

      reader.addEventListener("load", function () {
        const image = new Image();

        image.addEventListener("load", function () {
          imagePreview.innerHTML = "";
          imagePreview.appendChild(image);
        });

        image.src = reader.result;
      });

      reader.readAsDataURL(file);
    } else {
      imagePreview.innerHTML = "";
    }
  });

  function updateExpression() {
    const thoughtInput = document.getElementById('thought-input');
    const expressionH3 = document.getElementById('expression-h3');
    expressionH3.textContent = thoughtInput.value;
  }
</script>
</body>
</html>
