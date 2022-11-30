<?php 
include("../includes/init.php");
$title = "loginOption";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>E-Commerce Database</title>
  <link rel="stylesheet" href="../styles/all.css">
</head>

<!-- Page content -->
<div class="content">

</div>

<body>
  <?php include("../includes/header.php"); ?>
    <div id="loginsubmit">
      <a href="customerLogin.php" id="loginbutton">LOGIN as Customer</a>
    </div>

    <div id="loginsubmit">
      <a href="salesLogin.php" id="loginbutton">LOGIN as Merchant</a>
    </div>

    <div id="loginsubmit">
      <a href="../index.php" id="loginbutton">Continue as Guest</a>
    </div>

  <?php include("../includes/footer.php"); ?>

</body>

</html>
