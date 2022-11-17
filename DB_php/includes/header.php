<div>
    <li><a href="{{url_for('home')}}">Home</a></li>
    <li><a href="{{url_for('products')}}">Products</a></li>
    <li><a href="{{url_for('cart')}}">Cart</a></li>
</div>

<table class="nav">
  <tr>
    <td><a href="index.php" id="logo">Database System for E-Commerce</a></td>
    <td id="submitlink">
      <a href="#submit">Add New</a>
    </td>
  </tr>
</table>

<style>
    div {
      list-style-type: none;
      margin: 0;
      padding: 0;
      overflow: hidden;
    }
    
    li {
        float: left;
    }
    
    li a {
        display: block;
        color: black;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
    }
    
    li a:hover:not(.active) {
        background-color: black;
        color: white;

    }
    
</style>

    