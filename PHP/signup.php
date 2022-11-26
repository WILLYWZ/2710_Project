<?php 
    include("includes/init.php");
    $title = "signup";
    $db = open_sqlite_db("data/project.sqlite");
    $messages = array();

    // get list of customers
    $customerIDs = exec_sql_query($db, "SELECT customerID FROM Customers", NULL)->fetchAll(PDO::FETCH_COLUMN);
    $customerNames = exec_sql_query($db, "SELECT name FROM Customers", NULL)->fetchAll(PDO::FETCH_COLUMN);
    $customerAddresses = exec_sql_query($db, "SELECT address FROM Customers", NULL)->fetchAll(PDO::FETCH_COLUMN);
    $customerTypes = exec_sql_query($db, "SELECT kind FROM Customers", NULL)->fetchAll(PDO::FETCH_COLUMN);
    $customerPasswords = exec_sql_query($db, "SELECT customerPassword FROM Customers", NULL)->fetchAll(PDO::FETCH_COLUMN);


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $customerID = $_POST['customerID'];
        $customerName = $_POST['name'];
        $customerAddress = $_POST['address'];
        $customerType = $_POST['kind'];
        $customerPassword = $_POST['customerPassword'];

        $valid_review = TRUE;

        if (!in_array($customerID, $customerIDs)) {
            $valid_review = TRUE;
        } else {
            $valid_review = FALSE;
            array_push($messages, "The ID has been taken!");
        }

        if ($customerID == NULL) {
            $valid_review = FALSE;
            array_push($messages, "ID could not be empty!");
        }

        if ($customerName == NULL) {
            $valid_review = FALSE;
            array_push($messages, "Name could not be empty!");
        }

        if ($customerAddress == NULL) {
            $valid_review = FALSE;
            array_push($messages, "Address could not be empty!");
        }

        if ($customerType == NULL) {
            $valid_review = FALSE;
            array_push($messages, "Type could not be empty!");
        }

        if ($customerPassword == NULL) {
            $valid_review = FALSE;
            array_push($messages, "Please set a password!");
        }


        if ($valid_review) {
            $sql = "INSERT INTO Customers (customerID, name, address, kind, customerPassword) VALUES (:customerID, :name, :address, :kind, :customerPassword)";
            $params = array(
            ':customerID' => $customerID,
            ':name' => $customerName,
            ':address' => $customerAddress,
            ':kind' => $customerType,
            ':customerPassword' => $customerPassword,
            );
            // Insert valid product info into database
            $result = exec_sql_query($db, $sql, $params);
            if ($result) {
            unset($messages);
            $messages = array();
            array_push($messages, "Welcome $customerName! Your account has been created!");
            //array_push($messages, '<p> Login <a href="customerLogin.php">HERE</a> by your user ID and Password  </p>');
            }
            else {
            unset($messages);
            $messages = array();
            array_push($messages, "Could Not Create Account");
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
            <a href="index.php">Home</a>
            <a href="guestProducts.php">Products</a>
            <a href="guestStore.php">Store Locations</a>
            <a href="loginOption.php">LOGIN</a>
            <a class="active" href="signup.php">SIGN UP</a>
        </div>

        <div id="main">
            <?php
                // Write out any messages to the user.
                foreach ($messages as $message) {
                echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>\n";
                }
            ?>
        </div>

        <div id="submit">
            
            <h2>Create Account</h2>

            <form action="signup.php" method="post" novalidate>

                <div>
                    <label>ID:</label>
                    <input type="text" name="customerID" />
                </div>

                <div>
                    <label>Name:</label>
                    <input type="text" name="name" />
                </div>

                <div>
                    <label>Address: </label>
                    <input type="text" name="address" />
                </div>

                <div>
                    <label>Customers Type: </label>
                    <input type="text" name="kind" />
                </div>

                <div>
                    <label>Password: </label>
                    <input type="text" name="customerPassword" />
                </div>

                <div>
                    <button id="add" type="submit" value="submit">SIGN UP</button>
                </div>
            </form>
        </div>

        <?php include("includes/footer.php"); ?>

    </body>

</html>
