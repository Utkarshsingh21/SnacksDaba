<?php
if (!empty($_SESSION["cust_login"])) {?>
  <nav class="navbar">
    <a href="index.php" class="logo">SnacksDhaba</a>
    <form action="search_results.php" method="get">
      <input type="search" class="search" placeholder="Search Snacks" name="searchbtn">
    </form>
    <div class="goto">
      <a href="reccomendation_product.php" class="mybtn" style="color: white; font-weight: bold;">Recommendations</a>
      <div class="dropdown mt-1">
        <button type="button" class="btn btn-secondary dropdown-toggle"data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-circle fs-5"></i>
        </button>
        <ul class="dropdown-menu text-center bg-dark">
          <li><a href="customer_profile_edit.php" class="dropdown-item">Profile</a></li>
          <li><a href="change_customerPassword.php" class="dropdown-item">Change Password</a></li>
          <li><a href="address_table.php" class="dropdown-item">Addresses</a></li>
          <li><a href="myorder_table.php" class="dropdown-item">Orders</a></li>
          <li><a href="admin/main.php?flag=14" class="dropdown-item" onclick="return confirm('Are you sure you want to logout')">Logout</a></li>
        </ul>
      </div>
      <a href="cart.php" class="mybtn" style="background-color:mediumvioletred ; color: white; font-weight: bold;">Cart</a>
    </div>
  </nav>
  <?php
} 
else{?>
  <nav class="navbar">
    <a href="index.php" class="logo">SnacksDhaba</a>
    <form action="search_results.php" method="get">
      <input type="search" class="search" placeholder="Search Snacks" name="searchbtn">
    </form>
    <div class="goto">
      <a href="customer_login.php" class="mybtn" style="color: white; font-weight: bold;">LogIn</a>
      <a href="customer_signup.php" class="mybtn" style="background-color:orange ;color: white; font-weight: bold;">SignUp</a>
    </div>
  </nav>
  <?php
}
?>