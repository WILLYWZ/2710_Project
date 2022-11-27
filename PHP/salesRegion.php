<?php 
include("includes/init.php");
$title = "region";
$db = open_sqlite_db("data/project.sqlite");
$messages = array();

//login session
session_start();

//print seller ID
if ($_SESSION['logged_user_by_sql']) {
    print($_SESSION['logged_user_by_sql']);
}

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
    <td><?php echo htmlspecialchars($record["regionID"]); ?></td>
    <td><?php echo htmlspecialchars($record["regionName"]); ?></td>
    <td><?php echo htmlspecialchars($record["regionManager"]); ?></td>
  </tr>
<?php
}

const SEARCH_FIELDS = [
  "all" => "Select Search Category",
  "RegionID" => "By ID",
  "RegionName" => "By Name",
  "RegionManager" => "By Manager",
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
$regionID = exec_sql_query($db, "SELECT regionID FROM Region", NULL)->fetchAll(PDO::FETCH_COLUMN);
$regionName = exec_sql_query($db, "SELECT regionName FROM Region", NULL)->fetchAll(PDO::FETCH_COLUMN);
$regionManager = exec_sql_query($db, "SELECT regionManager FROM Region", NULL)->fetchAll(PDO::FETCH_COLUMN);

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
  <?php include("includes/headerSales.php"); ?>
  <div class="sidebar">
    <a href="salesHome.php">Home</a>
    <a href="salesProducts.php">Products</a>
    <a href="salesCustomers.php">Customers</a>
    <a href="salesTransactions.php">Transactions</a>
    <a href="salesOrder.php">Make a Order</a>
    <a class="active" href="salesRegion.php">Region</a>
    <a href="salesStore.php">Store</a>
    <a href="salesSalespersons.php">Salespersons</a>
    <a href="salesDataAggregation.php">Data Aggregation</a>
  </div>

  <div id="main">
    <?php
    // Write out any messages to the user.
    foreach ($messages as $message) {
      echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>\n";
    }
    ?>

    <form id="searchForm" action="salesRegion.php" method="get" novalidate>
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
      <h5>Search Results</h5>

      <?php
      if ($search_field == "all") {
        // Search across all fields
        $sql = "SELECT * FROM Region WHERE (regionID LIKE '%' || :search || '%') 
                                          OR (regionName LIKE '%' || :search || '%') 
                                          OR (regionManager LIKE '%' || :search || '%') ";
        $params = array(
          ':search' => $search
        );
      } else {
        // Search across the specified field
        $sql = "SELECT * FROM Region WHERE ($search_field LIKE '%' || :search || '%')";
        $params = array(
          ':search' => $search
        );
      }
    } else {
      ?>
      <h5>Region</h5>
      <?php
      $sql = "SELECT * FROM Region";
      $params = array();
    }

    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
      $records = $result->fetchAll();

      if (count($records) > 0) {
      ?>
        <table id = "region">
          <tr>
            <th>Region ID</th>
            <th>Region Name</th>
            <th>Region Manager</th>
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
