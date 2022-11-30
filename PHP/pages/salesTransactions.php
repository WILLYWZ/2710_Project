<?php 
    include("../includes/init.php");
    $title = "Transactions";
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
            <td><?php echo htmlspecialchars($record["transactionID"]); ?></td>
            <td><?php echo htmlspecialchars($record["orderNumber"]); ?></td>
            <td><?php echo htmlspecialchars($record["date"]); ?></td>
            <td><?php echo htmlspecialchars($record["SalespersonName"]); ?></td>
            <td><?php echo htmlspecialchars($record["ProductID"]); ?></td>
            <td><?php echo htmlspecialchars($record["price"]); ?></td>
            <td><?php echo htmlspecialchars($record["quantity"]); ?></td>
            <td><?php echo htmlspecialchars($record["customerID"]); ?></td>
            <td><?php echo htmlspecialchars($record["regionID"]); ?></td>
        </tr>
        <?php
    }

    const SEARCH_FIELDS = [
        "All" => "Select All",
        "transactionID" => "By Transaction ID",
        "date" => "By Date",
        "SalespersonName" => "By Salesperson",
        "ProductID" => "By Product ID",
        "price" => "Price Under",
        "customerID" => "By Customer ID",
        "regionID" => "By Region ID",
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
    $transactionids = exec_sql_query($db, "SELECT transactionID FROM Transactions", NULL)->fetchAll(PDO::FETCH_COLUMN);
    $ordernumbers = exec_sql_query($db, "SELECT orderNumber FROM Transactions", NULL)->fetchAll(PDO::FETCH_COLUMN);
    $productids = exec_sql_query($db, "SELECT productID FROM Transactions", NULL)->fetchAll(PDO::FETCH_COLUMN);
    $customerids = exec_sql_query($db, "SELECT customerID FROM Transactions", NULL)->fetchAll(PDO::FETCH_COLUMN);

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
            <a href="salesCustomers.php">Customers</a>
            <a class="active" href="salesTransactions.php">Transactions</a>
            <a href="salesOrder.php">Make a Order</a>
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

            <form id="searchForm" action="salesTransactions.php" method="get" novalidate>
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
                        if ($search_field == "All") {
                            // Search across all fields
                            $sql = "SELECT * FROM transactions ";
                            $params = array();
                        } else if ($search_field == "price"){
                            // Search across the specified field
                            $sql = "SELECT * FROM transactions WHERE ($search_field <= :search )";
                            $params = array(
                            ':search' => $search
                            );
                        }else {
                            // Search across the specified field
                            $sql = "SELECT * FROM transactions WHERE ($search_field == :search )";
                            $params = array(
                            ':search' => $search
                            );
                        }
                } else {
                    ?>
                    <h5>Transactions</h5>
                    <?php
                        $sql = "SELECT * FROM transactions";
                        $params = array();
                }

                $result = exec_sql_query($db, $sql, $params);
            
                if ($result) {
                    $records = $result->fetchAll();

                    if (count($records) > 0) {
                        ?>
                            <table id = "transactions">
                                <tr>
                                    <th>transactionID</th>
                                    <th>orderNumber</th>
                                    <th>date</th>
                                    <th>Salesperson</th>
                                    <th>productID</th>
                                    <th>price</th>
                                    <th>quantity</th>
                                    <th>customerID</th>
                                    <th>regionID</th>
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
        <br>
        <br>
        <?php include("../includes/footer.php"); ?>

    </body>

</html>
