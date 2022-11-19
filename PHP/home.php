<?php 
include("includes/init.php");
$title = "home";
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
    <a class="active" href="home.php">Home</a>
    <a href="products.php">Products</a>
    <a href="customers.php">Customers</a>
    <a href="transactions.php">Transactions</a>
    <a href="region.php">Region</a>
    <a href="store.php">Store</a>
    <a href="salespersons.php">Salespersons</a>
  </div>


  <?php include("includes/footer.php"); ?>

</body>

</html>
