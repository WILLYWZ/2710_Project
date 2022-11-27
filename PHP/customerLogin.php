<?php 
include("includes/init.php");
$title = "customerLogin";
$db = open_sqlite_db("data/project.sqlite");
session_start();
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>E-Commerce Database</title>
  <link rel="stylesheet" href="styles/all.css">
</head>

<body>
    <?php
        if (isset($_SESSION['logged_user_by_sql'])) {
			include 'includes/headerCustomer.php';
		}
        else{
			include 'includes/header.php';
		}
    ?>
    <div id="scloginsubmit">

        <h4>Customer Login</h4>
            <form action="customerLogin.php" method="post">
            <div>
                <label>Customer ID:</label>
                <input type="text" name="customerID" />
            </div>
            <div>
                <label>Password:</label>
                <input type="password" name="customerPassword" />
            </div>
            <div>
                <button type="submit" value="submit">LOGIN</button>
            </div>

        <?php
                $post_username = $_POST['customerID'];
                $post_password = $_POST['customerPassword'];

                //print($post_username);
                //print($post_password);

                $sql = "SELECT * FROM Customers WHERE customerID = :name AND customerPassword = :password";

                $statement = $db->prepare($sql);
                $statement->execute(array('name' => $post_username, 'password' => $post_password));
                $row = $statement->fetch();
                
                if ($row['customerID'] == $post_username && $row['customerPassword'] == $post_password) {
                    //print('Login Successful');
                    $db_username = $row['customerID'];
                    $_SESSION['logged_user_by_sql'] = $db_username;
                }

                if (isset($_SESSION['logged_user_by_sql'])) {
                    print("<p>Congratulations, $db_username! You have logged in.<p>");
                    print('<p>Click <a href="customerInfo.php">HERE</a> to access your account </p>');
                } else {
                    print('<p>You did not login successfully. Please <a href="customerLogin.php">try</a> again. </p>');
                }
                
            ?>

    </div>


</body>

</html>

