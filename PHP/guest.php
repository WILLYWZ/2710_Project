<?php 
include("includes/init.php");
$title = "home";
// This is the page for user to login to the system
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>E-Commerce Database</title>
  <link rel="stylesheet" href="styles/all.css">
</head>

<!-- Page content -->
<div class="content">

</div>

<body>
  <?php include("includes/headerGuest.php"); ?>

  <div class="sidebar">
    <a class="active" href="guest.php">Home</a>
    <a href="productsGuest.php">Products</a>
  </div>


  <?php include("includes/footer.php"); ?>

</body>

</html>