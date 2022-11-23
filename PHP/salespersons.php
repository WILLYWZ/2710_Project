<?php 
include("includes/init.php");
$title = "salespersons";
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
    <td><?php echo htmlspecialchars($record["name"]); ?></td>
    <td><?php echo htmlspecialchars($record["address"]); ?></td>
    <td><?php echo htmlspecialchars($record["email"]); ?></td>
    <td><?php echo htmlspecialchars($record["jobTitle"]); ?></td>
    <td><?php echo htmlspecialchars($record["storeAssigned"]); ?></td>
    <td><?php echo htmlspecialchars($record["salary"]); ?></td>
  </tr>
<?php
}

const SEARCH_FIELDS = [
  "all" => "Select Search Category",
  "name" => "By Name",
  "address" => "By Address",
  "email" => "By Email",
  "jobTitle" => "By Job Title",
  "storeAssigned" => "By Store Assigned",
  "salary" => "By Salary"
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
$names = exec_sql_query($db, "SELECT name FROM Salespersons", NULL)->fetchAll(PDO::FETCH_COLUMN);
$addresses = exec_sql_query($db, "SELECT address FROM Salespersons", NULL)->fetchAll(PDO::FETCH_COLUMN);
$emails = exec_sql_query($db, "SELECT email FROM Salespersons", NULL)->fetchAll(PDO::FETCH_COLUMN);
$jobTitles = exec_sql_query($db, "SELECT jobTitle FROM Salespersons", NULL)->fetchAll(PDO::FETCH_COLUMN);
$storeAssigneds = exec_sql_query($db, "SELECT storeAssigned FROM Salespersons", NULL)->fetchAll(PDO::FETCH_COLUMN);
$salarys = exec_sql_query($db, "SELECT salary FROM Salespersons", NULL)->fetchAll(PDO::FETCH_COLUMN);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $name = $_POST['name'];
  $address = $_POST['address'];
  $email = $_POST['email'];
  $jobTitle = $_POST['jobTitle'];
  $storeAssigned = $_POST['storeAssigned'];
  $salary = $_POST['salary'];

  $valid_review = TRUE;

  if (!in_array($name, $names)) {
    $valid_review = TRUE;
  } else {
    $valid_review = FALSE;
    array_push($messages, "name already exists!");
  }

  if ($name == NULL) {
    $valid_review = FALSE;
    array_push($messages, "name could not be empty!");
  }

  if ($address == NULL) {
    $valid_review = FALSE;
    array_push($messages, "address could not be empty!");
  }

  if ($email == NULL) {
    $valid_review = FALSE;
    array_push($messages, "email could not be empty!");
  }

  if ($jobTitle == NULL) {
    $valid_review = FALSE;
    array_push($messages, "jobTitle could not be empty!");
  }

  if ($storeAssigned == NULL) {
    $valid_review = FALSE;
    array_push($messages, "storeAssigned could not be empty!");
  }

  if ($salary == NULL || $salary < 0) {
    $valid_review = FALSE;
    array_push($messages, "salary should be greater than 0!");
  }


  if ($valid_review) {
    $sql = "INSERT INTO Salespersons (name, address, email, jobTitle, storeAssigned, salary) VALUES (:name, :address, :email, :jobTitle, :storeAssigned, :salary)";
    $params = array(
      ':name' => $name,
      ':address' => $address,
      ':email' => $email,
      ':jobTitle' => $jobTitle,
      ':storeAssigned' => $storeAssigned,
      ':salary' => $salary,
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
  <?php include("includes/header.php"); ?>
  <div class="sidebar">
    <a href="home.php">Home</a>
    <a href="products.php">Products</a>
    <a href="customers.php">Customers</a>
    <a href="transactions.php">Transactions</a>
    <a href="region.php">Region</a>
    <a href="store.php">Store</a>
    <a class="active" href="salespersons.php">Salespersons</a>
  </div>

  <div id="main">
    <?php
    // Write out any messages to the user.
    foreach ($messages as $message) {
      echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>\n";
    }
    ?>

    <form id="searchForm" action="salespersons.php" method="get" novalidate>
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
        $sql = "SELECT * FROM Salespersons WHERE (name LIKE '%' || :search || '%') 
                                          OR (address LIKE '%' || :search || '%') 
                                          OR (email LIKE '%' || :search || '%') 
                                          OR (jobTitle LIKE '%' || :search || '%')
                                          OR (storeAssigned LIKE '%' || :search || '%') ;
                                          OR (salary LIKE '%' || :search || '%') ";
        $params = array(
          ':search' => $search
        );
      } else {
        // Search across the specified field
        $sql = "SELECT * FROM Salespersons WHERE ($search_field LIKE '%' || :search || '%')";
        $params = array(
          ':search' => $search
        );
      }
    } else {
      ?>
      <h2>Salespersons</h2>
      <?php
      $sql = "SELECT * FROM Salespersons";
      $params = array();
    }

    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
      $records = $result->fetchAll();

      if (count($records) > 0) {
      ?>
        <table id = "salespersons">
          <tr>
            <th>Name</th>
            <th>Address</th>
            <th>Email</th>
            <th>Job title</th>
            <th>Store Assigned</th>
            <th>Salary</th>
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
    <h2>Add New Salesperson</h2>

    <form action="salespersons.php" method="post" novalidate>

      <div>
        <label>Name:</label>
        <input type="text" name="name" />
      </div>

      <div>
        <label>address:</label>
        <input type="text" name="address" />
      </div>

      <div>
        <label>email: </label>
        <input type="text" name="email" />
      </div>

      <div>
        <label>Job title </label>
        <input type="text" name="jobTitle" />
      </div>

      <div>
        <label>Store assigned </label>
        <input type="text" name="storeAssigned" />
      </div>
      
      <div>
        <label>Salary </label>
        <input type="number" name="salary" />
      </div>

      <div>
        <button id="add" type="submit" value="submit">Add Salesperson</button>
      </div>
    </form>
  </div>

  <?php include("includes/footer.php"); ?>

</body>

</html>
