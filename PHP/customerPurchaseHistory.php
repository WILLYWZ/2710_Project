<?php 
include("includes/init.php");
$title = "customerInfo";
$db = open_sqlite_db("data/project.sqlite");
$messages = array();
// This is the home page for logged-in Customer
session_start();

//print customer ID
$session_id = $_SESSION['logged_user_by_sql'];
if ($_SESSION['logged_user_by_sql']) {
    print($_SESSION['logged_user_by_sql']);
}

function print_orderRecord($record)
{
?>
  <tr>
    <td><?php echo htmlspecialchars($record["orderNumber"]); ?></td>
    <td><?php echo htmlspecialchars($record["date"]); ?></td>
    <td><?php echo htmlspecialchars($record["ProductID"]); ?></td>
    <td><?php echo htmlspecialchars($record["price"]); ?></td>
    <td><?php echo htmlspecialchars($record["quantity"]); ?></td>
  </tr>
<?php
}

function print_priceAndQuality($record)
{
?>
  <tr>
    <td><?php echo htmlspecialchars($record["price"]); ?></td>
    <td><?php echo htmlspecialchars($record["quantity"]); ?></td>
  </tr>
<?php
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

  <div>
    <h3>Order Total</h3>
  </div>

  <div id="main1">
    <?php
    //print($session_id);
    $sql1 = " SELECT price, quantity FROM Transactions WHERE customerID == '$session_id' ";
    $results1 = exec_sql_query($db, $sql1, NULL);
    if ($results1) {
      $records1 = $results1->fetchAll();
      if (count($records1) > 0) {
        //print(count($records1));
    ?>
        <?php
        foreach ($records1 as $record1) {
          $total = $total + $record1['price']*$record1['quantity'];
        }
        echo " "."<br>"." ";
        echo "$".$total
      ?>
    <?php
      } else {
        // No results found
        echo " "."<br>"." ";
        echo "<p> You have not made any purchase. </p>";
      }
    }
    ?>


  <div>
    <h3>Purchase History</h3>
  </div>

  <div id="main">
    <?php
    // Write out any messages to the user.
      foreach ($messages as $message) {
        echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>\n";
      }
    ?>
    <?php
    //print($session_id);
    $sql = " SELECT orderNumber, date, ProductID, price, quantity FROM Transactions WHERE customerID == '$session_id' ";
    $results = exec_sql_query($db, $sql, NULL);
    if ($results) {
      $records = $results->fetchAll();
      if (count($records) > 0) {
      ?>

      <table id = "products">
          <tr>
            <th>Order Number</th>
            <th>Date</th>
            <th>Product ID</th>
            <th>Price</th>
            <th>Quality</th>
          </tr>
          <?php
          foreach ($records as $record) {
            print_orderRecord($record);
          }
          ?>
        </table>
    <?php
      } else {
        // No results found
      }
    }
    ?>
  

  <?php include("includes/footer.php"); ?>

</body>

</html>