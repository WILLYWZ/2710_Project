<?php 
include("includes/init.php");
$title = "dataAggregation";
$db = open_sqlite_db("data/project.sqlite");
$messages = array();

function loop($values)
{
  foreach ($values as $value) {
    echo "<option value=\"" . htmlspecialchars($value) . "\">" . htmlspecialchars($value) . "</option>";
  }
}

const SEARCH_FIELDS = [
  "all" => "",
  "sales" => "Sales",
  "profit" => "Profit",
  "type" => "Type",
  "region" => "Region Sales",
  "product" => "Product(Business)",
];

  $do_search = TRUE;
  // check if the category exists
  $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_STRING);
  if (in_array($category, array_keys(SEARCH_FIELDS))) {
    $search_field = $category;
  } 
  else {
    //array_push($messages, "Invalid Category");
    $do_search = FALSE;
  }

  // Get search terms
  $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
  $search = trim($search);

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>E-Commerce Database</title>
  <link rel="stylesheet" href="styles/all.css">
</head>

<body>

  <?php include("includes/header.php"); ?>
  <div class="sidebar">
    <a  href="home.php">Home</a>
    <a href="products.php">Products</a>
    <a href="customers.php">Customers</a>
    <a href="transactions.php">Transactions</a>
    <a href="order.php">Make a Order</a>
    <a href="region.php">Region</a>
    <a href="store.php">Store</a>
    <a href="salespersons.php">Salespersons</a>
    <a class="active" href="dataAggregation.php">Data Aggregation</a>
    <a href="loginOption.php">LOG IN</a>
    <a href="logout.php">LOG OUT</a>
  </div>

  <div id="main">
    <?php
    // Write out any messages to the user.
    foreach ($messages as $message) {
      echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>\n";
    }
    ?>
    <h2> Sort By: </h2>
    <form id="searchForm" action="dataAggregation.php" method="get" novalidate>
      <select name="category">
        <?php foreach (SEARCH_FIELDS as $field_name => $label) { ?>
          <option value="<?php echo htmlspecialchars($field_name); ?>">
          <?php echo htmlspecialchars($label); ?></option>
        <?php } ?>
      </select>
      <!--<input type="text" name="search" required />-->
      <button type="submit">Search</button>
    </form>


    <?php
    if ($do_search) {
    ?>
      <h2>Results</h2>

      <?php
      if ($search_field == "sales") {
        // Search across all fields
        $sql = "SELECT ProductName, SUM(price*quantity) AS Sales, SUM((price-ProductPrice)*quantity) AS Profit
                FROM Products AS P, Transactions AS T
                WHERE P.ProductID = T.ProductID
                GROUP BY ProductName
                ORDER BY Sales DESC;";
      } 
      elseif($search_field == "profit"){
        // Search across the specified field
        $sql = "SELECT ProductName, SUM(price*quantity) AS Sales, SUM((price-ProductPrice)*quantity) AS Profit
                FROM Products AS P, Transactions AS T
                WHERE P.ProductID = T.ProductID
                GROUP BY ProductName
                ORDER BY Profit DESC;";
      }
      elseif($search_field == "type"){
        // Search across the specified field
        $sql = "SELECT ProductType,  price*quantity AS Sales, (price-ProductPrice)*quantity AS  Profit
                FROM Products AS P, Transactions AS T
                WHERE P.ProductID = T.ProductID
                GROUP BY ProductType
                ORDER BY Profit DESC;
        ";
      } 
      elseif ($search_field == "region"){
        $sql = "SELECT R.RegionID AS RegionID, R.RegionName AS RegionName, SUM(price*quantity) AS Sales
                FROM Region AS R, Transactions AS T
                WHERE R.RegionID = T.regionID
                GROUP BY R.RegionID
                ORDER BY Sales DESC;";
      }
      elseif ($search_field == "product"){
              $sql = "SELECT C.customerID, C.name, C.kind, P.ProductID,P.ProductName, SUM(price*quantity) AS Sales
              FROM Customers AS C, Transactions AS T, Products AS P
              WHERE C.kind = 'business' AND C.customerID = T.customerID AND T.ProductID = P.ProductID
              GROUP BY P.ProductID;";
      }
    } 
    else {
      ?>
      <h2>Data Aggregation</h2>
      <?php
      $sql = "SELECT * FROM Products;";
      
      //$params = array();
    }?>

    <?php
    //store query into result
    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
      $records = $result->fetchAll();

      if ($search_field == "sales") {
      ?>
        <table id = "sales">
          <tr>
            <th>ProductName</th>
            <th>Sales</th>
            <th>Profit</th>
          </tr>
          <?php
          foreach ($records as $record) {
            ?>
            <tr>
            <td><?php echo htmlspecialchars($record["ProductName"]); ?></td>
            <td><?php echo htmlspecialchars($record["Sales"]); ?></td>
            <td><?php echo htmlspecialchars($record["Profit"]); ?></td>
            </tr>
            <?php
          }
          ?>
        </table>
    <?php
      } elseif ($search_field == "profit") {
        ?>
          <table id = "profit">
            <tr>
              <th>ProductName</th>
              <th>Sales</th>
              <th>Profit</th>
            </tr>
  
            <?php
            foreach ($records as $record) {
              ?>
              <tr>
              <td><?php echo htmlspecialchars($record["ProductName"]); ?></td>
              <td><?php echo htmlspecialchars($record["Sales"]); ?></td>
              <td><?php echo htmlspecialchars($record["Profit"]); ?></td>
              </tr>
              <?php
            }
            ?>
          </table>
      <?php
      } elseif ($search_field == "type") {
        ?>
          <table id = "profit">
            <tr>
              <th>ProductType</th>
              <th>Sales</th>
              <th>Profit</th>
            </tr>
  
            <?php
            foreach ($records as $record) {
              ?>
              <tr>
              <td><?php echo htmlspecialchars($record["ProductType"]); ?></td>
              <td><?php echo htmlspecialchars($record["Sales"]); ?></td>
              <td><?php echo htmlspecialchars($record["Profit"]); ?></td>
              </tr>
              <?php
            }
            ?>
          </table>
      <?php
      } elseif ($search_field == "region") {
        ?>
          <table id = "region">
            <tr>
              <th>RegionID</th>
              <th>RegionName</th>
              <th>Sales</th>
            </tr>
            <?php
            foreach ($records as $record) {
              ?>
              <tr>
              <td><?php echo htmlspecialchars($record["RegionID"]); ?></td>
              <td><?php echo htmlspecialchars($record["RegionName"]); ?></td>
              <td><?php echo htmlspecialchars($record["Sales"]); ?></td>
              </tr>
              <?php
            }
            ?>
          </table>
      <?php
      } elseif ($search_field == "product") {
        ?>
          <table id = "product">
            <tr>
              <th>CustomerID</th>
              <th>Name</th>
              <th>Kind</th>
              <th>ProductID</th>
              <th>ProductName</th>
              <th>Sales</th>
            </tr>
  
            <?php
            foreach ($records as $record) {
              ?>
              <tr>
              <td><?php echo htmlspecialchars($record["customerID"]); ?></td>
              <td><?php echo htmlspecialchars($record["name"]); ?></td>
              <td><?php echo htmlspecialchars($record["kind"]); ?></td>
              <td><?php echo htmlspecialchars($record["ProductID"]); ?></td>
              <td><?php echo htmlspecialchars($record["ProductName"]); ?></td>
              <td><?php echo htmlspecialchars($record["Sales"]); ?></td>
              </tr>
              <?php
            }
            ?>
          </table>
      <?php
      } else {
    // No results found
          echo "<p> Please Select From Drop Down Menu </p>";
      }
    }
    ?>
  </div>

  <?php include("includes/footer.php"); ?>

</body>

</html>
