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
    print($session_id);
}

function print_customerID($record)
{
?>
  <tr>
    <td><?php echo htmlspecialchars($record["customerID"]); ?></td>
  </tr>
<?php
}

function print_customerName($record)
{
?>
  <tr>
    <td><?php echo htmlspecialchars($record["name"]); ?></td>
  </tr>
<?php
}

function print_customerAddress($record)
{
?>
  <tr>
    <td><?php echo htmlspecialchars($record["address"]); ?></td>
  </tr>
<?php
}

function print_customerKind($record)
{
?>
  <tr>
    <td><?php echo htmlspecialchars($record["kind"]); ?></td>
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
    <a class="active" href="customerInfo.php">Account</a>
    <a href="customerPurchaseHistory.php">Purchase History</a>
    <a href="productsCustomer.php">Products Gallery</a>
    <a href="customerStore.php">Check Our Locations</a>
  </div>
  
  <div>
    <h3>Account Details</h3>
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
    $sql = " SELECT customerID, name, address, kind FROM Customers WHERE customerID == '$session_id' ";
    $results = exec_sql_query($db, $sql, NULL);
    //print(count($results));
    if ($results) {
      $records = $results->fetchAll();
      if (count($records) > 0) {
      ?>
          <?php
          foreach ($records as $record) {
            echo "ID:"."<br>"." ";
            echo $record['customerID']."<br>"." ";
            echo "Name: "."<br>"." ";
            echo $record['name']."<br>"." ";
            echo "Address: "."<br>"." ";
            echo $record['address']."<br>"." ";
            echo "Account Type: "."<br>"." ";
            echo $record['kind']."<br>"." ";
            //print_customerID($record);
            //print_customerName($record);
            //print_customerAddress($record);
            //print_customerKind($record);
          }
          ?>
    <?php
      } else {
        // No results found
        echo "<p> No Match Found. </p>";
      }
    }
    ?>



  </div>





  <?php include("includes/footer.php"); ?>

</body>

</html>