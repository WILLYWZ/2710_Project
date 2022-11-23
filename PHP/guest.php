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
  <?php include("includes/headerHome.php"); ?>

  <div class="sidebar">
    <a class="active" href="guest.php">Home</a>
    <a href="productsGuest.php">Products</a>
    <a href="loginOption.php">Back to Login</a>
    <a href="createAccount.php">Register As New User</a>
  </div>


  <?php include("includes/footer.php"); ?>

</body>

</html>