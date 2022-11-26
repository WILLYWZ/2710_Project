<?php 
include("includes/init.php");
$title = "customerStore";
$db = open_sqlite_db("data/project.sqlite");
$messages = array();

// This is the home page for logged-in Customer
session_start();

//print customer ID
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
    <td><?php echo htmlspecialchars($record["storeID"]); ?></td>
    <td><?php echo htmlspecialchars($record["address"]); ?></td>
    <td><?php echo htmlspecialchars($record["manager"]); ?></td>
    <td><?php echo htmlspecialchars($record["salesHeadCount"]); ?></td>
    <td><?php echo htmlspecialchars($record["regionID"]); ?></td>
  </tr>
<?php
}

//Search drop down menu
const SEARCH_FIELDS = [
  "all" => "Search From All",
  "storeID" => "By storeID",
  "address" => "By Address",
  "manager" => "By manager",
  "salesHeadCount" => "By salesHeadCount",
  "regionID" => "By regionID",
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
$storeIDs = exec_sql_query($db, "SELECT storeID FROM Store", NULL)->fetchAll(PDO::FETCH_COLUMN);
$addresses = exec_sql_query($db, "SELECT address FROM Store", NULL)->fetchAll(PDO::FETCH_COLUMN);
$managers = exec_sql_query($db, "SELECT manager FROM Store", NULL)->fetchAll(PDO::FETCH_COLUMN);
$salesHeadCounts = exec_sql_query($db, "SELECT salesHeadCount FROM Store", NULL)->fetchAll(PDO::FETCH_COLUMN);
$regionIDs = exec_sql_query($db, "SELECT regionID FROM Store", NULL)->fetchAll(PDO::FETCH_COLUMN);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $storeID = $_POST['storeID'];
  $address = $_POST['address'];
  $manager = $_POST['manager'];
  $salesHeadCount = $_POST['salesHeadCount'];
  $regionID = $_POST['regionID'];

  $valid_review = TRUE;

  if (!in_array($storeID, $storeIDs)) {
    $valid_review = TRUE;
  } else {
    $valid_review = FALSE;
    array_push($messages, "storeID already exists!");
  }

  if ($storeID == NULL) {
    $valid_review = FALSE;
    array_push($messages, "storeID could not be empty!");
  }

  if ($address == NULL) {
    $valid_review = FALSE;
    array_push($messages, "address could not be empty!");
  }

  if ($manager == NULL) {
    $valid_review = FALSE;
    array_push($messages, "manager could not be empty!");
  }

  if ($salesHeadCount == NULL || $salesHeadCount < 0) {
    $valid_review = FALSE;
    array_push($messages, "salesHeadCount should be greater than 0!");
  }

  if ($regionID == NULL) {
    $valid_review = FALSE;
    array_push($messages, "regionID could not be empty!");
  }




  if ($valid_review) {
    $sql = "INSERT INTO Store (storeID, address, manager, salesHeadCount, regionID) VALUES (:storeID, :address, :manager, :salesHeadCount, :regionID)";
    $params = array(
      ':storeID' => $storeID,
      ':address' => $address,
      ':manager' => $manager,
      ':salesHeadCount' => $salesHeadCount,
      ':regionID' => $regionID,
    );
    // Insert valid product info into database
    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
      unset($messages);
      $messages = array();
      array_push($messages, "Entry Successfully Added");
    }
    else {
      unset($messages);
      $messages = array();
      array_push($messages, "Could Not Add Entry");
    }
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
  <?php include("includes/headerCustomer.php"); ?>

  <div class="sidebar">
    <a href="customerInfo.php">Account</a>
    <a href="customerPurchaseHistory.php">Purchase History</a>
    <a href="customerProducts.php">Products Gallery</a>
    <a class="active" href="customerStore.php">Check Our Locations</a>
  </div>

  <div id="main">
    <?php
    // Write out any messages to the user.
    foreach ($messages as $message) {
      echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>\n";
    }
    ?>

    <form id="searchForm" action="customerStore.php" method="get" novalidate>
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
        $sql = "SELECT * FROM Store WHERE (storeID LIKE '%' || :search || '%') 
                                          OR (address LIKE '%' || :search || '%') 
                                          OR (manager LIKE '%' || :search || '%') 
                                          OR (salesHeadCount LIKE '%' || :search || '%')
                                          OR (regionID LIKE '%' || :search || '%') ";
        $params = array(
          ':search' => $search
        );
      } else {
        // Search across the specified field
        $sql = "SELECT * FROM Store WHERE ($search_field LIKE '%' || :search || '%')";
        $params = array(
          ':search' => $search
        );
      }
    } else {
      ?>
      <h2>Store</h2>
      <?php
      $sql = "SELECT * FROM Store";
      $params = array();
    }

    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
      $records = $result->fetchAll();

      if (count($records) > 0) {
      ?>
        <table id = "store">
          <tr>
            <th>StoreID</th>
            <th>Address</th>
            <th>Manager</th>
            <th>SalesHeadCount</th>
            <th>RegionID</th>
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