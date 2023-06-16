 <?php 
session_start();

if (!isset($_SESSION['email'])) {
  header('Location:  http://localhost/gym-project2/login.php');
  exit;
}

require "connection.php";

$stmt = $conn->prepare("SELECT name, email, tel_number, profile_picture FROM users WHERE email = ?");
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();


if (isset($_POST['submit'])) {
  $name = $_POST['username'];
  $tel_number = $_POST['tel_number'];

  if (!empty($_FILES['image']['name'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check === false) {
      echo "File is not an image.";
      $uploadOk = 0;
    }

    if ($_FILES["image"]["size"] > 500000) {
      echo "Sorry, your file is too large.";
      $uploadOk = 0;
    }

  
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
      echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      $uploadOk = 0;
    }

    if ($uploadOk == 0) {
      if ($uploadOk == 1) {
        unlink($_FILES["image"]["tmp_name"]);
      }

      $_POST['username'] = '';
      $_POST['tel_number'] = '';
    } else {

      while (file_exists($target_file)) {
        $basename = basename($_FILES["image"]["name"], "." . $imageFileType);
        $i = 1;
        while (file_exists($target_dir . $basename . "_" . $i . "." . $imageFileType)) {
          $i++;
        }
        $target_file = $target_dir . $basename . "_" . $i . "." . $imageFileType;
      }

    
      if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars( basename( $_FILES["image"]["name"])). " has been uploaded.";

      
        $stmt= $conn->prepare("UPDATE users SET name = ?, tel_number = ?, profile_picture = ? WHERE email = ?");
        $stmt->bind_param("ssss", $name, $tel_number, $target_file, $_SESSION['email']);
        $stmt->execute();
        if ($stmt->error) {
          die("Error updating data: " . $stmt->error);
        }

    
        header('Location: http://localhost/gym-project2/editprofile.php');
        die;
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    }
  } else {

$stmt= $conn->prepare("UPDATE users SET name = ?, tel_number = ? WHERE email = ?");
$stmt->bind_param("sss", $name, $tel_number, $_SESSION['email']);
$stmt->execute();
if ($stmt->error) {
  die("Error updating data: " . $stmt->error);
}


header('Location: http://localhost/gym-project2/editprofile.php');
die;
  }
}

?> 

<!DOCTYPE html>
<html>
<head>
  <title>Edit Profile</title>
  <link rel="stylesheet" href="editprofile.css">
</head>

<body>
    <div class="container">
      <h1>Edit Profile</h1>
  <form method="Post" enctype="multipart/form-data">
    <div class="left">
      <div class="image">
      <img src="<?php echo $row['profile_picture']; ?>" id="preview">
      </div>
      <label for="image" class="upload-btn">Upload Photo</label>
      <input type="file" name="image" id="image" accept="image/*" class="file-input" required>
    </div>
    <div class="right">
      <div class="input">
        <label for="username">Name</label>
        <input type="text" id="username" name="username" value="<?php echo $row['name']; ?>" required >
      </div>
        <div class="input">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" readonly>
        </div>
      

      
        <div class="input">
          <label for="telephone">Phone</label>
          <input type="text" id="telephone" name="tel_number" value="<?php echo $row['tel_number']; ?>" >
        </div>
    

      <div class="actions">
        <div class="link"><a href="Home.php">Main Page</a></div>
        <div class="link"><a href="editpass.php">Edit Password</a></div>
        <input type="submit" id="submit-btn" name="submit" value="Save">
      </div>
</form>
 
    </div>
    
<script>
  function previewImage(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById("preview").src = e.target.result;
        console.log("success")
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  document.getElementById("image").addEventListener("change", function() {
    previewImage(this);
  });




</script>

</body>
</html>
