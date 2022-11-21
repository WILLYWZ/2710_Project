<?php 
    include("includes/init.php");
    $title = "Transactions";
    $db = open_sqlite_db("data/project.sqlite");
    $messages = array();

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
        "transactionID" => "By transactionID",
        "date" => "By Date",
        "SalespersonName" => "By Salesperson",
        "ProductID" => "By ProductID",
        "price" => "Price Under",
        "customerID" => "By customerID",
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
    $transactionids = exec_sql_query($db, "SELECT transactionID FROM Transactions", NULL)->fetchAll(PDO::FETCH_COLUMN);
    $ordernumbers = exec_sql_query($db, "SELECT orderNumber FROM Transactions", NULL)->fetchAll(PDO::FETCH_COLUMN);
    $productids = exec_sql_query($db, "SELECT productID FROM Transactions", NULL)->fetchAll(PDO::FETCH_COLUMN);
    $customerids = exec_sql_query($db, "SELECT customerID FROM Transactions", NULL)->fetchAll(PDO::FETCH_COLUMN);


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
        if ($date == NULL) {
            $valid_review = FALSE;
            array_push($messages, "Date could not be empty!");
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
            array_push($messages, "Quatity could not be empty!");
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
            ':regionID'=> $customerid,
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
            <a class="active" href="transactions.php">Transactions</a>
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

            <form id="searchForm" action="transactions.php" method="get" novalidate>
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
                    <h2>Transactions</h2>
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

        <div id="submit">
            
            <h2>Add New Customers</h2>

            <form action="transactions.php" method="post" novalidate>

                <div>
                    <label>transaction ID:</label>
                    <input type="text" name="transactionID" />
                </div>

                <div>
                    <label>OrderNumber:</label>
                    <input type="text" name="orderNumber" />
                </div>

                <div>
                    <label>date: </label>
                    <input type="date" name="date" />
                </div>

                <div>
                    <label>SalespersonName: </label>
                    <input type="text" name="SalespersonName" />
                </div>

                <div>
                    <label>Product ID: </label>
                    <input type="text" name="productID" />
                </div>
                
                <div>
                    <label>Price: </label>
                    <input type="text" name="price" />
                </div>
                
                <div>
                    <label>Quantity: </label>
                    <input type="text" name="quantity" />
                </div>

                <div>
                    <label>Customer ID: </label>
                    <input type="text" name="customerID" />
                </div>

                <div>
                    <label>Region ID: </label>
                    <input type="text" name="regionID" />
                </div>
                <div>
                    <button id="add" type="submit" value="submit">Add Transaction</button>
                </div>
            </form>
        </div>

        <?php include("includes/footer.php"); ?>

    </body>

</html>
