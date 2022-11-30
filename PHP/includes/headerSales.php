<table class="nav">
  <tr>
    <td href="../pages/salesHome.php" id="logo">
    <?php
      $link_address1 = "../pages/salesHome.php";
      if($_SESSION['logged_user_by_sql']){
        echo "<a href='".$link_address1."'>Welcome</a>".htmlspecialchars($_SESSION['logged_user_by_sql']) . "! </div>";
      } 
      else{
        echo "Welcome! Please LOGIN or Continue as Guest!";
      }
    ?>
    </td>
    <td id="submitlinklogout">
      <a href="../pages/logout.php">LOGOUT</a>
    </td>
  </tr>
</table>

