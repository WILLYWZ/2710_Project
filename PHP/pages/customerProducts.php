<?php 
include("../includes/init.php");
$title = "productsCustomer";
$db = open_sqlite_db("../data/project.sqlite");
$messages = array();

session_start();
//if ($_SESSION['logged_user_by_sql']) {
  //print($_SESSION['logged_user_by_sql']);
//}

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
  "all" => "Search From All",
  "ProductID" => "By ID",
  "ProductName" => "By Name",
  "InventoryAmount" => "By Stock",
  "ProductPrice" => "Price Under",
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
  <link rel="stylesheet" href="../styles/all.css">
</head>

<body>
  <?php include("../includes/headerCustomer.php"); ?>
  <div class="sidebar">
    <a href="customerInfo.php">Account</a>
    <a href="customerPurchaseHistory.php">Purchase History</a>
    <a class="active" href="customerProducts.php">Products Gallery</a>
    <a href="customerStore.php">Locations</a>
  </div>

  <div id="main">
    <?php
    // Write out any messages to the user.
    foreach ($messages as $message) {
      echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>\n";
    }
    ?>

    <?php
    $search = (isset($_GET['search'])) ? htmlentities($_GET['search']) : '';
    ?>

    <form id="searchForm" action="customerProducts.php" method="get" novalidate>
      <select name="category">
        <?php foreach (SEARCH_FIELDS as $field_name => $label) { ?>
          <option value="<?php echo htmlspecialchars($field_name); ?>"><?php echo htmlspecialchars($label); ?></option>
        <?php } ?>
      </select>
      <input type="text" name="search" value="<?= $search ?>" required />
      <button type="submit">FILTER</button>
    </form>


    <?php
    if ($do_search) {
    ?>
      <h5>Search Results</h5>

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
      } else if ($search_field == "ProductPrice"){
        // Search across the specified field
        $sql = "SELECT * FROM Products WHERE ($search_field <= :search )";
        $params = array(
        ':search' => $search
        );
      }else {
        // Search across the specified field
        $sql = "SELECT * FROM Products WHERE ($search_field LIKE '%' || :search || '%')";
        $params = array(
          ':search' => $search
        );
      }
    } else {
      ?>
      <h5>Products List</h5>
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
            <th>ID</th>
            <th>NAME</th>
            <th>STOCK</th>
            <th>PRICE</th>
            <th>TYPE</th>
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

  <?php include("../includes/footer.php"); ?>

</body>

</html>
