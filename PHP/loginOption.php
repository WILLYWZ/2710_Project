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
  <?php include("includes/headerHome.php"); ?>
    <div id="submit">
      <a href="customerLogin.php">Login as Customer</a>
    </div>

    <div id="submit">
      <a href="sellerLogin.php">Login as Merchant</a>
    </div>

    <div id="submit">
      <a href="guest.php">Continue As Guest</a>
    </div>

  <?php include("includes/footer.php"); ?>

</body>

</html>
