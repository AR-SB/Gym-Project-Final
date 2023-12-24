  <?php 
require "connection.php";
session_start();
$error ='';
if (isset($_POST['submit'])) {
  $em = $_POST['email'];
  $pass = $_POST['password'];
  $name = $_POST['username'];
  $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

 
  $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
  $stmt->bind_param("s", $em);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
   $error = "User Already created with this email.";
  } else {

   
    $target_dir = "/uploads";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

   
    if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

 
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

 
    if ($uploadOk == 0) {
        if ($uploadOk == 1) {
            unlink($_FILES["image"]["tmp_name"]);
        }
   
        $_POST['email'] = '';
        $_POST['password'] = '';
        $_POST['username'] = '';
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

   
            $stmt= $conn->prepare("INSERT INTO users (email,password,name,profile_picture) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $em, $hashedPassword, $name, $target_file);
            $stmt->execute();
            if ($stmt->error) {
                die("Error inserting data: " . $stmt->error);
            }

            header('Location: http://localhost/gym-project-php//login.php');
            die;
        } else {
            echo "Sorry, there was an error uploading your file." . $_FILES["image"]["error"];


        }
      }
    }
  }
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link rel="stylesheet" href="signup.css">
</head>
<body>
  <div class="container">
    <div class="image-container">
      <div class="image-preview"></div>
    </div>
    <div class="form-container">
      <h1>Sign Up</h1>
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="input-box">
          
          <input type="text" name="username" id="name" placeholder="Enter Your Name" required>
  
         
          <input type="email" name="email" id="email" placeholder="Enter Your Email" required>
  
         
          <input type="password" name="password" id="password" placeholder="Enter Your Password" required>
          <label for="image" class="upload-btn">Upload Photo</label>
          <input type="file" name="image" id="image" accept="image/*" class="file-input" required>
          
  
          <input type="submit" name="submit" value="Sign Up">
        </div>
       
        
        <a href="login.php">Already Have An Account?</a>

       
      </form>
    </div>

    <?php if (!empty($error)) : ?>
              <p id="error"><?php echo $error; ?></p>
              <?php endif; ?>
    
  </div>
  <script src="script.js"></script>
</body>
</html>