<?php 
include("../includes/init.php");
$title = "home";
$db = open_sqlite_db("../data/project.sqlite");
$messages = array();
// This is the page for user to login to the system
session_start();

//print seller ID
$session_id = $_SESSION['logged_user_by_sql'];
//if ($_SESSION['logged_user_by_sql']) {
  //print($_SESSION['logged_user_by_sql']);
//}

function print_salesRecord($record)
{
?>
  <tr>
    <td><?php echo htmlspecialchars($record["transactionID"]); ?></td>
    <td><?php echo htmlspecialchars($record["date"]); ?></td>
    <td><?php echo htmlspecialchars($record["ProductID"]); ?></td>
    <td><?php echo htmlspecialchars($record["price"]); ?></td>
    <td><?php echo htmlspecialchars($record["quantity"]); ?></td>
    <td><?php echo htmlspecialchars($record["customerID"]); ?></td>
  </tr>
<?php
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>ComforTABLE</title>
  <link rel="stylesheet" href="../styles/all.css">
</head>

<!-- Page content -->
<div class="content">

</div>

<body>
  <?php include("../includes/headerSales.php"); ?>

  <div class="sidebar">
    <a class="active" href="salesHome.php">Home</a>
    <a href="salesProducts.php">Products</a>
    <a href="salesCustomers.php">Customers</a>
    <a href="salesTransactions.php">Transactions</a>
    <a href="salesOrder.php">Place an Order</a>
    <a href="salesRegion.php">Region</a>
    <a href="salesStore.php">Store</a>
    <a href="salesSalespersons.php">Salespersons</a>
    <a href="salesDataAggregation.php">Data Aggregation</a>
  </div>


  <div>
    <h3>Sales Summary</h3>
  </div>

  <div id="main1">
    <?php
    //print($session_id);
    $sql_salesName = "SELECT name FROM Salespersons WHERE email = '$session_id' ";
    $result_name = exec_sql_query($db, $sql_salesName, NULL);
    //print(count($records_name));
    if ($result_name){
      $records_names = $result_name->fetchAll();
      if (count($records_names) > 0){
        foreach ($records_names as $record_name){
          //print($record_name['name']);
        }
      }
    }
    
    $salesName = $record_name['name'];
    //print($salesName);

    $sql1 = " SELECT price, quantity FROM Transactions WHERE SalespersonName == '$salesName' ";
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
        echo "<p> You have not sold anything. </p>";
      }
    }
    ?>

<div>
    <h3>Sales History</h3>
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
    $sql = " SELECT transactionID, date, ProductID, price, quantity, customerID FROM Transactions WHERE SalespersonName == '$salesName' ";
    $results = exec_sql_query($db, $sql, NULL);
    if ($results) {
      $records = $results->fetchAll();
      if (count($records) > 0) {
      ?>

      <table id = "products">
          <tr>
            <th>Transaction ID</th>
            <th>Date</th>
            <th>Product ID</th>
            <th>Price</th>
            <th>Quality</th>
            <th>Customer</th>
          </tr>
          <?php
          foreach ($records as $record) {
            print_salesRecord($record);
          }
          ?>
        </table>
    <?php
      } else {
        // No results found
      }
    }
    ?>


  <?php include("../includes/footer.php"); ?>

</body>

</html>
