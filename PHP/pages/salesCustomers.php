<?php 
    include("../includes/init.php");
    $title = "Customers";
    $db = open_sqlite_db("../data/project.sqlite");
    $messages = array();

    //login session
    session_start();

    //print seller ID
    //if ($_SESSION['logged_user_by_sql']) {
        //print($_SESSION['logged_user_by_sql']);
    //}

    function loop($values){
        foreach ($values as $value) {
            echo "<option value=\"" . htmlspecialchars($value) . "\">" . htmlspecialchars($value) . "</option>";
        }
    }

    function print_record($record){
        ?>
        <tr>
            <td><?php echo htmlspecialchars($record["customerID"]); ?></td>
            <td><?php echo htmlspecialchars($record["name"]); ?></td>
            <td><?php echo htmlspecialchars($record["address"]); ?></td>
            <td><?php echo htmlspecialchars($record["kind"]); ?></td>
        </tr>
        <?php
    }

    const SEARCH_FIELDS = [
        "All" => "Select All",
        "customerID" => "By ID",
        "name" => "By Name",
        "address" => "By Address",
        "kind" => "By Type",
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
    $customerids = exec_sql_query($db, "SELECT customerID FROM Customers", NULL)->fetchAll(PDO::FETCH_COLUMN);
    $customernames = exec_sql_query($db, "SELECT name FROM Customers", NULL)->fetchAll(PDO::FETCH_COLUMN);
    $customeraddress = exec_sql_query($db, "SELECT address FROM Customers", NULL)->fetchAll(PDO::FETCH_COLUMN);
    $customertype = exec_sql_query($db, "SELECT kind FROM Customers", NULL)->fetchAll(PDO::FETCH_COLUMN);


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $customerid = $_POST['customerid'];
        $customername = $_POST['customername'];
        $address = $_POST['address'];
        $kind = $_POST['kind'];

        $valid_review = TRUE;

        if (!in_array($customerid, $customerids)) {
            $valid_review = TRUE;
        } else {
            $valid_review = FALSE;
            array_push($messages, "Customer ID exists!");
        }

        if ($customerid == NULL) {
            $valid_review = FALSE;
            array_push($messages, "Customer ID could not be empty!");
        }

        if ($customername == NULL) {
            $valid_review = FALSE;
            array_push($messages, "Customer Name could not be empty!");
        }

        if ($address == NULL) {
            $valid_review = FALSE;
            array_push($messages, "Customer address could not be empty!");
        }

        if ($kind == NULL) {
            $valid_review = FALSE;
            array_push($messages, "Customer Type could not be empty!");
        }


        if ($valid_review) {
            $sql = "INSERT INTO Customers (customerID, name, address, kind) VALUES (:customerID, :name, :address, :kind)";
            $params = array(
            ':customerID' => $customerid,
            ':name' => $customername,
            ':address' => $address,
            ':kind' => $kind,
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
        <link rel="stylesheet" href="../styles/all.css">
    </head>

    <body>
        <?php include("../includes/headerSales.php"); ?>
        <div class="sidebar">
            <a href="salesHome.php">Home</a>
            <a href="salesProducts.php">Products</a>
            <a class="active" href="salesCustomers.php">Customers</a>
            <a href="salesTransactions.php">Transactions</a>
            <a href="salesOrder.php">Place an Order</a>
            <a href="salesRegion.php">Region</a>
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

            <?php
                $search = (isset($_GET['search'])) ? htmlentities($_GET['search']) : '';
            ?>

            <form id="searchForm" action="salesCustomers.php" method="get" novalidate>
                <select name="category">
                    <?php foreach (SEARCH_FIELDS as $field_name => $label) { ?>
                    <option value="<?php echo htmlspecialchars($field_name); ?>"><?php echo htmlspecialchars($label); ?></option>
                    <?php } ?>
                </select>

                <input type="text" name="search" value="<?= $search ?>" required />

                <button type="submit">Search</button>
            </form>


            <?php
                if ($do_search) {
                    ?>
                    <h5>Search Results</h5>

                    <?php
                        if ($search_field == "All") {
                            // Search across all fields
                            $sql = "SELECT customerID, name, address, kind FROM Customers ";
                            $params = array();
                        } else if ($search_field == "address") {
                            // Search across the specified field
                            $sql = "SELECT customerID, name, address, kind FROM Customers 
                                        WHERE (address LIKE '%' || :search || '%')";
                            $params = array(
                            ':search' => $search
                            );
                        }else {
                            // Search across the specified field
                            $sql = "SELECT customerID, name, address, kind FROM Customers WHERE ($search_field == :search )";
                            $params = array(
                            ':search' => $search
                            );
                        }
                } else {
                    ?>
                    <h5>Customers List</h5>
                    <?php
                        $sql = "SELECT customerID, name, address, kind FROM Customers";
                        $params = array();
                }

                $result = exec_sql_query($db, $sql, $params);
            
                if ($result) {
                    $records = $result->fetchAll();

                    if (count($records) > 0) {
                        ?>
                            <table id = "Customers">
                                <tr>
                                    <th>Customers ID</th>
                                    <th>Customers Name</th>
                                    <th>Address</th>
                                    <th>Customers Type</th>
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
            
            <h2>Add New Customers</h2>

            <form action="salesCustomers.php" method="post" novalidate>

                <div>
                    <label>Customers ID:</label>
                    <input type="text" name="customerid" />
                </div>

                <div>
                    <label>Customers Name:</label>
                    <input type="text" name="customername" />
                </div>

                <div>
                    <label>Customers Address: </label>
                    <input type="text" name="address" />
                </div>

                <div>
                    <label>Customers Type </label>
                    <input type="text" name="kind" />
                </div>


                <div>
                    <button id="add" type="submit" value="submit">Add Customers</button>
                </div>
            </form>
        </div>

        <?php include("../includes/footer.php"); ?>

    </body>

</html>
