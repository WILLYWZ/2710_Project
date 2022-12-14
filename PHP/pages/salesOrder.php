<?php 
    include("../includes/init.php");
    $title = "products";
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
                <td><?php echo htmlspecialchars($record["ProductID"]); ?></td>
                <td><?php echo htmlspecialchars($record["ProductName"]); ?></td>
                <td><?php echo htmlspecialchars($record["InventoryAmount"]); ?></td>
                <td><?php echo htmlspecialchars($record["ProductPrice"]); ?></td>
                <td><?php echo htmlspecialchars($record["ProductType"]); ?></td>
            </tr>
        <?php
    }

    const SEARCH_FIELDS = [
        "all" => "Select all",
        "ProductID" => "By ID",
        "ProductName" => "By Name",
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
    $transactionids = exec_sql_query($db, "SELECT transactionID FROM Transactions", NULL)->fetchAll(PDO::FETCH_COLUMN);
    $ordernumbers = exec_sql_query($db, "SELECT orderNumber FROM Transactions", NULL)->fetchAll(PDO::FETCH_COLUMN);

    $productids = exec_sql_query($db, "SELECT productID FROM Transactions", NULL)->fetchAll(PDO::FETCH_COLUMN);
    $salespersonnames = exec_sql_query($db, "SELECT SalespersonName FROM Transactions", NULL)->fetchAll(PDO::FETCH_COLUMN);
    $customerids = exec_sql_query($db, "SELECT customerID FROM Transactions", NULL)->fetchAll(PDO::FETCH_COLUMN);
    $regionids = exec_sql_query($db, "SELECT regionID FROM Transactions", NULL)->fetchAll(PDO::FETCH_COLUMN);
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $transactionid = $_POST['transactionID'];
        $ordernumber = $_POST['orderNumber'];
        $date = $_POST['date'];
        $salespersonname = $_POST['SalespersonName'];

        $productid = $_POST['productID'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $customerid = $_POST['customerID'];
        $regionid = $_POST['regionID'];

        $stock = exec_sql_query($db, "SELECT InventoryAmount FROM Products WHERE ProductID = '$productid'", NULL)->fetchAll(PDO::FETCH_COLUMN);


        $valid_review = TRUE;

        //Transaction ID
        if (!in_array($transactionid, $transactionids)) {
            $valid_review = TRUE;
        } else {
            $valid_review = FALSE;
            array_push($messages, "Transaction ID exists!");
        }

        if ($transactionid == NULL) {
            $valid_review = FALSE;
            array_push($messages, "Transaction ID could not be empty!");
        }
        //Transaction ID

        //Order Number
        if (!in_array($ordernumber, $ordernumbers)) {
            $valid_review = TRUE;
        } else {
            $valid_review = FALSE;
            array_push($messages, "Order Number exists!");
        }

        if ($ordernumber == NULL) {
            $valid_review = FALSE;
            array_push($messages, "Order Number could not be empty!");
        }


        //Date
        if ($date == NULL || $date == 0 ) {
            $valid_review = FALSE;
            array_push($messages, "Date could not be empty or zero!");
        }


        //Salesperson Name
        if ($salespersonname == NULL) {
            $valid_review = FALSE;
            array_push($messages, "Salesperson Name could not be empty!");
        }


        //Product ID
        if ($productid == NULL) {
            $valid_review = FALSE;
            array_push($messages, "Product ID could not be empty!");
        }


        //Price
        if ($price == NULL) {
            $valid_review = FALSE;
            array_push($messages, "Price could not be empty!");
        }


        //Quantity
        if ($quantity == NULL) {
            $valid_review = FALSE;
            array_push($messages, "Quantity could not be empty!");
        }
        if ($quantity > $stock[0]) {
            $valid_review = FALSE;
            array_push($messages, "Quatity could not be greater than stock!");
            array_push($messages, "{$productid} currently have {$stock[0]} in stock! ");
        }


        //Customer ID
        if ($customerid == NULL) {
            $valid_review = FALSE;
            array_push($messages, "Customer ID could not be empty!");
        }

        if ($regionid == NULL) {
            $valid_review = FALSE;
            array_push($messages, "Region ID could not be empty!");
        }


        if ($valid_review) {
            $sql = "INSERT INTO Transactions 
                    (transactionID, orderNumber, date, SalespersonName, productID, price, quantity, customerID, regionID) 
                    VALUES (:transactionID, :orderNumber, :date, :SalespersonName, :productID, :price, :quantity, :customerID, :regionID)";
            $params = array(
            ':transactionID'=> $transactionid,
            ':orderNumber'=> $ordernumber,
            ':date'=> $date,
            ':SalespersonName'=> $salespersonname,
            ':productID'=> $productid,
            ':price'=> $price,
            ':quantity'=> $quantity,
            ':customerID'=> $customerid,
            ':regionID'=> $regionid,
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

            $newStock = $stock[0] - $quantity;
            $stock = exec_sql_query($db, "UPDATE Products SET InventoryAmount = '$newStock'
                                            WHERE ProductID = '$productid'", NULL)->fetchAll(PDO::FETCH_COLUMN);
        }
    }
?>



<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>ComforTABLE</title>
        <link rel="stylesheet" href="../styles/all.css">
    </head>

    <body>
        <?php include("../includes/headerSales.php"); ?>
        <div class="sidebar">
            <a href="salesHome.php">Home</a>
            <a href="salesProducts.php">Products</a>
            <a href="salesCustomers.php">Customers</a>
            <a href="salesTransactions.php">Transactions</a>
            <a class="active" href="salesOrder.php">Place an Order</a>
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

            <form id="searchForm" action="salesOrder.php" method="get" novalidate>
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
                        if ($search_field == "all") {
                            // Search across all fields
                            $sql = "SELECT * FROM Products ";
                            $params = array();
                        }else if ($search_field == "ProductPrice") {
                            // Search across all fields
                            $sql = "SELECT * FROM Products WHERE ($search_field <= :search) ";
                            $params = array(
                            ':search' => $search
                            );
                        }else if ($search_field == "ProductName") {
                                // Search across all fields
                                $sql = "SELECT * FROM Products WHERE ($search_field like '%' || :search || '%') ";
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
                }  else {
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
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Stock</th>
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
            
            <h2>Place New Order</h2>

            <form action="salesOrder.php" method="post" novalidate>

            

            <div>
                <label>transaction ID:</label>
                <input type="text" name="transactionID" value="<?= $transactionid ?>" />
            </div>

            <div>
                <label>OrderNumber:</label>
                <input type="text" name="orderNumber" value="<?= $ordernumber ?>" />
            </div>

            <div>
                <label>date: </label>
                <input type="date" name="date" value="<?= $date ?>" />
            </div>

            <div>
                <label>SalespersonName: </label>
                <select name="SalespersonName">
                    <?php
                        $sales = array_unique($salespersonnames);
                        foreach ($sales as $value) { 
                            ?>
                                <option value="<?= $value ?>"><?= $value ?></option>
                            <?php
                        } 
                    ?>
                </select>
            </div>

            <div>
                <label>Product ID: </label>
                <select name="productID">
                    <?php
                        $products = exec_sql_query($db, "SELECT productID FROM Products", NULL)->fetchAll(PDO::FETCH_COLUMN);
                        $product = array_unique($products);
                        foreach ($product as $value) { 
                            ?>
                                <option value="<?= $value ?>"><?= $value ?></option>
                            <?php
                        } 
                    ?>
                </select>
            </div>

            <div>
                <label>Price: </label>
                <input type="text" name="price" value="<?= $price ?>"/>
            </div>

            <div>
                <label>Quantity: </label>
                <input type="number" name="quantity" min=1 value="<?= $quantity ?>" />
            </div>

            <div>
                <label>Customer ID: </label>
                <select name="customerID">
                    <?php
                        $customers = exec_sql_query($db, "SELECT customerID FROM Customers", NULL)->fetchAll(PDO::FETCH_COLUMN);
                        $customer= array_unique($customers);
                        foreach ($customer as $value) { 
                            ?>
                                <option value="<?= $value ?>"><?= $value ?></option>
                            <?php
                        } 
                    ?>
                </select>
            </div>

            <div>
                <label>Region ID: </label>
                <select name="regionID">
                    <?php
                        $regions = exec_sql_query($db, "SELECT regionID FROM Region", NULL)->fetchAll(PDO::FETCH_COLUMN);
                        $region= array_unique($regions);
                        foreach ($region as $value) { 
                            ?>
                                <option value="<?= $value ?>"><?= $value ?></option>
                            <?php
                        } 
                    ?>
                </select>
            </div>

            <div>
                <button id="add" type="submit" value="submit">Add Transaction</button>
            </div>
        </form>
    </div>

    <?php include("../includes/footer.php"); ?>

    </body>

</html>
