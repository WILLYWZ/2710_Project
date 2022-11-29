<?php 
include("includes/init.php");
$title = "guest";
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
  <?php include("includes/header.php"); ?>

  <div class="sidebar">
    <a class="active" href="index.php">Home</a>
    <a href="guestProducts.php">Products</a>
    <a href="guestStore.php">Locations</a>
    <a href="loginOption.php">LOGIN</a>
    <a href="signup.php">SIGN UP</a>
  </div>


  <?php include("includes/footer.php"); ?>

</body>

</html>