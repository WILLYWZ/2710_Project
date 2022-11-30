<?php
	//Need to start a session in order to access it to be able to end it
	session_start();

	if (isset($_SESSION['logged_user_by_sql'])) {
		$olduser = $_SESSION['logged_user_by_sql'];
		unset($_SESSION['logged_user_by_sql']);
	} else {
		$olduser = false;
	}
?>

<!DOCTYPE html>
<html>
	<body>

	<?php
    if ( isset($_SESSION['logged_user_by_sql'] ) ) {
            include '../includes/header.php';
        }else{
            include '../includes/header.php';
        }
    ?>
	<div id="content">

		<?php
			//echo '<pre>' . print_r( $_SESSION, true ) . '</pre>';
			if ( $olduser ) {
				print("<p>Thanks for using this site, $olduser!</p>");
				print("<p>Return to <a href='loginOption.php'>login page</a></p>");
			} else {
				print("<p>You haven't logged in.</p>");
				print("<p>Go to <a href='loginOption.php'>login page</a></p>");
			}
		?>
	</div>
	</body>
</html>