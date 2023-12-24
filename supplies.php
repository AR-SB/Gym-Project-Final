<?php
session_start();

// Assuming you have a database connection file named "connection.php"
require "connection.php";

// Fetch supplies from the database
$stmt = $conn->prepare("SELECT id, name, price, description, image_path FROM supplies");
$stmt->execute();
$result = $stmt->get_result();
$supplies = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fitness World</title>
  <link rel="stylesheet" href="supplies.css">
  <script src="/jquery-3.6.4.min.js"></script>
</head>
<body>
  
  <header class="header">
    <nav class="navbar">
      <a href="home.php">Home Page</a>
    </nav>

    <form action="" class="search-bar">

      <input type="text" placeholder="Search...">
      <button><ion-icon name="search-outline"></ion-icon></button>
    </form>
  </header>
  <div id="background"></div>
    
  </div>
  <div class="protien-container">
    <div class="line1">
      <?php foreach ($supplies as $supply) { ?>
        <div class="card">
          <div class="title"><h2><?php echo $supply['name']; ?></h2></div>
          <div class="card-img">
            <img src="<?php echo $supply['image_path']; ?>" alt="">
          </div>
          <div class="content">
            <div class="price">
              <h2><?php echo $supply['price']; ?> USD</h2>
            </div>
            <div class="description">
              <?php echo $supply['description']; ?>
            </div>
            <div class="actions">
              <a href="#"><ion-icon name="cart-outline"></ion-icon>Add to cart</a>
              <a href="#"><ion-icon name="cash-outline"></ion-icon>Purchase</a>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
  <script>

  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>