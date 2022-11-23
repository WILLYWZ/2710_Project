<?php 
include("includes/init.php");
$title = "customerInfo";
// This is the home page for logged-in Customer
session_start();

//print customer ID
if ($_SESSION['logged_user_by_sql']) {
    print($_SESSION['logged_user_by_sql']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>E-Commerce Database</title>
  <link rel="stylesheet" href="styles/all.css">
</head>

<div class="content"></div>

<body>
  <?php include("includes/headerCustomer.php"); ?>

  <div class="sidebar">
    <a href="customerAccount.php">Home</a>
    <a href="customerInfo.php">Account</a>
    <a class="active" href="customerPurchaseHistory.php">Purchase History</a>
    <a href="productsCustomer.php">Products Gallery</a>
    <a href="customerStore.php">Check Our Locations</a>
  </div>

  <?php include("includes/footer.php"); ?>

</body>

</html>