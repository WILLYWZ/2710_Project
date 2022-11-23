<table class="nav">
  <tr>
    <td href="customerAccount.php" id="logo">
    <?php 
      if($_SESSION['logged_user_by_sql']){
        echo "<div id='User'> Welcome " . htmlspecialchars($_SESSION['logged_user_by_sql']) . "! </div>";
      } 
      else{
        echo "Welcome! Please LOGIN or Continue as Guest!";
      }
    ?>
    </td>
    <td id="submitlink">
      <a href="logout.php">LOGOUT</a>
    </td>
  </tr>
</table>
