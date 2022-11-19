<?php 
include("includes/init.php");
$title = "products";
$db = open_sqlite_db("data/Products.sqlite");
$messages = array();

function loop($values)
{
  foreach ($values as $value) {
    echo "<option value=\"" . htmlspecialchars($value) . "\">" . htmlspecialchars($value) . "</option>";
  }
}
function print_record($record)
{
?>
  <tr>
    <td><?php echo htmlspecialchars($record["ProductID"]); ?></td>
    <td><?php echo htmlspecialchars($record["ProductName"]); ?></td>
    <td><?php echo htmlspecialchars($record["InventoryAmount"]); ?></td>
    <td><?php echo htmlspecialchars($record["ProductPrice"]); ?></td>
    <td><?php echo htmlspecialchars($record["ProductType"]); ?></td>
  </tr>
<?php
}

const SEARCH_FIELDS = [
  "all" => "Select Search Category",
  "ProductID" => "By ID",
  "ProductName" => "By Name",
  "InventoryAmount" => "By Stock",
  "ProductPrice" => "By Price",
  "ProductType" => "By Type",
];

if (isset($_GET['search'])) {
  $do_search = TRUE;

  // check if the category exists
  $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_STRING);
  if (in_array($category, array_keys(SEARCH_FIELDS))) {
    $search_field = $category;
  } else {
    array_push($messages, "Invalid Category");
    $do_search = FALSE;
  }

  // Get search terms
  $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
  $search = trim($search);
} else {
  $do_search = FALSE;
  $category = NULL;
  $search = NULL;
}



// Insert Form
$productids = exec_sql_query($db, "SELECT ProductID FROM Products", NULL)->fetchAll(PDO::FETCH_COLUMN);
$productnames = exec_sql_query($db, "SELECT ProductName FROM Products", NULL)->fetchAll(PDO::FETCH_COLUMN);
$inventoryamounts = exec_sql_query($db, "SELECT InventoryAmount FROM Products", NULL)->fetchAll(PDO::FETCH_COLUMN);
$productprices = exec_sql_query($db, "SELECT ProductPrice FROM Products", NULL)->fetchAll(PDO::FETCH_COLUMN);
$producttypes = exec_sql_query($db, "SELECT ProductType FROM Products", NULL)->fetchAll(PDO::FETCH_COLUMN);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $valid_review = TRUE;

  $productid = filter_input(INPUT_POST, 'ProductID', FILTER_SANITIZE_STRING);
  $productname = filter_input(INPUT_POST, 'ProductName', FILTER_SANITIZE_STRING);
  $inventoryamount = filter_input(INPUT_POST, 'InventoryAmount', FILTER_SANITIZE_STRING);
  $productprice = filter_input(INPUT_POST, 'ProductPrice', FILTER_SANITIZE_STRING);
  $producttype = filter_input(INPUT_POST, 'ProductType', FILTER_SANITIZE_STRING);


  if ($inventoryamount < 0) {
    $valid_review = FALSE;
  }
  if (in_array($productid, $productids)) {
    $valid_review = FALSE;
  }


  // Insert valid product info into database
  if ($valid_review) {
    $sql = "INSERT INTO Products (ProductID, ProductName, InventoryAmount, ProductPrice, ProductType) VALUES (:ProductID, :ProductName, :InventoryAmount, :ProductPrice, :ProductType)";
    $params = array(
      ':ProductID' => $productid,
      ':ProductName' => $productname,
      ':InventoryAmount' => $inventoryamount,
      ':ProductPrice' => $productprice,
      ':ProductType' => $producttype,
    );
    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
      array_push($messages, "Product has been added to the database");
    } else {
      array_push($messages, "Failed to add Product.");
    }
  } else {
    array_push($messages, "Failed to add Product. All fields are required. Product ID must be unique");
  }
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

<body>
  <?php include("includes/header.php"); ?>
  <div class="sidebar">
    <a href="home.php">Home</a>
    <a class="active" href="products.php">Products</a>
    <a href="customers.php">Customers</a>
    <a href="transactions.php">Transactions</a>
    <a href="region.php">Region</a>
    <a href="store.php">Store</a>
    <a href="salespersons.php">Salespersons</a>
  </div>

  <div id="main">
    <?php
    // Write out any messages to the user.
    foreach ($messages as $message) {
      echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>\n";
    }
    ?>

    <form id="searchForm" action="products.php" method="get" novalidate>
      <select name="category">
        <?php foreach (SEARCH_FIELDS as $field_name => $label) { ?>
          <option value="<?php echo htmlspecialchars($field_name); ?>"><?php echo htmlspecialchars($label); ?></option>
        <?php } ?>
      </select>
      <input type="text" name="search" required />
      <button type="submit">Search</button>
    </form>


    <?php
    if ($do_search) {
    ?>
      <h2>Search Results</h2>

      <?php
      if ($search_field == "all") {
        // Search across all fields
        $sql = "SELECT * FROM Products WHERE (ProductID LIKE '%' || :search || '%') 
                                          OR (ProductName LIKE '%' || :search || '%') 
                                          OR (InventoryAmount LIKE '%' || :search || '%') 
                                          OR (ProductPrice LIKE '%' || :search || '%')
                                          OR (ProductType LIKE '%' || :search || '%') ";
        $params = array(
          ':search' => $search
        );
      } else {
        // Search across the specified field
        $sql = "SELECT * FROM Products WHERE ($search_field LIKE '%' || :search || '%')";
        $params = array(
          ':search' => $search
        );
      }
    } else {
      ?>
      <h2>Products List</h2>
      <?php
      $sql = "SELECT * FROM Products";
      $params = array();
    }

    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
      $records = $result->fetchAll();

      if (count($records) > 0) {
      ?>
        <table id = "products">
          <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Inventory Amount</th>
            <th>Product Price</th>
            <th>Product Type</th>
          </tr>

          <?php
          foreach ($records as $record) {
            print_record($record);
          }
          ?>
        </table>
    <?php
      } else {
        // No results found
        echo "<p> No Match Found. </p>";
      }
    }
    ?>
  </div>
  <div id="submit">
    <h2>Add New Product</h2>

    <form action="products.php" method="post" novalidate>

      <div>
        <label>Product ID:</label>
        <input type="text" name="productid" />
      </div>

      <div>
        <label>Product Name:</label>
        <input type="text" name="productname" />
      </div>

      <div>
        <label>Inventory Amount: </label>
        <input type="integer" name="inventoryamount" />
      </div>

      <div>
        <label>Product Price </label>
        <input type="numeric" name="productprice" />
      </div>

      <div>
        <label>Product Type </label>
        <input type="text" name="producttype" />
      </div>


      <div>
        <span>
          <!-- empty element; used to align submit button --></span>
        <button id="add" type="submit">Add Product</button>
      </div>
    </form>
  </div>

  <?php include("includes/footer.php"); ?>

</body>

</html>
