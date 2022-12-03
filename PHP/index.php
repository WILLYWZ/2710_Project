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

  <title>ComforTABLE</title>
  <link rel="stylesheet" href="styles/all.css">
</head>

<!-- Page content -->
<div class="content">

</div>

<body>
  <?php include("includes/header.php"); ?>

  <div class="sidebar">
    <a class="active" href="index.php">Home</a>
    <a href="pages/guestProducts.php">Products</a>
    <a href="pages/guestStore.php">Locations</a>
    <a href="pages/loginOption.php">LOGIN</a>
    <a href="pages/signup.php">SIGN UP</a>
  </div>

  <div id="main">
    <h6>‘ComforTABLE’ is a shopping website for various types of Kitchen Wares and Tablewares.</h6>
    <h7>The system is written in PHP and styles with CSS. <br></h7>
    <h7>The Database is kept in SQLite. </h7>
  </div>


  <?php include("includes/footer.php"); ?>

</body>

</html>