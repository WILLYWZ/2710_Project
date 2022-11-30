<?php 
include("../includes/init.php");
$title = "salesLogin";
$db = open_sqlite_db("../data/project.sqlite");
session_start();
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>E-Commerce Database</title>
  <link rel="stylesheet" href="../styles/all.css">
</head>

<body>
    <?php
        if (isset($_SESSION['logged_user_by_sql'])) {
			include '../includes/headerSales.php';
		}
        else{
			include '../includes/header.php';
		}
    ?>
    <div id="scloginsubmit">

        <h4>Salesperson Login</h4>
            <form action="salesLogin.php" method="post">
            <div>
                <label>Email:</label>
                <input type="text" name="salesEmail" />
            </div>
            <div>
                <label>Password:</label>
                <input type="password" name="salesPassword" />
            </div>
            <div>
                <button type="submit" value="submit">LOGIN</button>
            </div>

        <?php
                $post_username = $_POST['salesEmail'];
                $post_password = $_POST['salesPassword'];

                //print($post_username);
                //print($post_password);

                $sql = "SELECT * FROM Salespersons WHERE email = :name AND salespersonPassword = :password";

                $statement = $db->prepare($sql);
                $statement->execute(array('name' => $post_username, 'password' => $post_password));
                $row = $statement->fetch();
                
                if ($row['email'] == $post_username && $row['salespersonPassword'] == $post_password) {
                    //print('Login Successful');
                    $db_username = $row['email'];
                    $_SESSION['logged_user_by_sql'] = $db_username;
                }

                if (isset($_SESSION['logged_user_by_sql'])) {
                    print("<p>Congratulations, $db_username! You have logged in.<p>");
                    print('<p>Click <a href="salesHome.php">HERE</a> to access your account </p>');
                } else {
                    print('<p>You did not login successfully. Please <a href="salesLogin.php">try</a> again. </p>');
                }
                
            ?>

    </div>


</body>

</html>

