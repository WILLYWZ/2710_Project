<?php 
include("includes/init.php");
$title = "products";
$db = open_sqlite_db("data/project.sqlite");
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

// get list of products
$productids = exec_sql_query($db, "SELECT ProductID FROM Products", NULL)->fetchAll(PDO::FETCH_COLUMN);
$productnames = exec_sql_query($db, "SELECT ProductName FROM Products", NULL)->fetchAll(PDO::FETCH_COLUMN);
$inventoryamounts = exec_sql_query($db, "SELECT InventoryAmount FROM Products", NULL)->fetchAll(PDO::FETCH_COLUMN);
$productprices = exec_sql_query($db, "SELECT ProductPrice FROM Products", NULL)->fetchAll(PDO::FETCH_COLUMN);
$producttypes = exec_sql_query($db, "SELECT ProductType FROM Products", NULL)->fetchAll(PDO::FETCH_COLUMN);
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
  <?php include("includes/headerGuest.php"); ?>
  <div class="sidebar">
    <a href="guest.php">Home</a>
    <a class="active" href="productsGuest.php">Products</a>
  </div>

  <div id="main">
    <?php
    // Write out any messages to the user.
    foreach ($messages as $message) {
      echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>\n";
    }
    ?>

    <form id="searchForm" action="productsGuest.php" method="get" novalidate>
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

  <?php include("includes/footer.php"); ?>

</body>

</html>
