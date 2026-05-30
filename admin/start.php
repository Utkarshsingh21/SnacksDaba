<?php
session_start();
if(!isset($_SESSION["admin_id"])){
  header("location:admin_login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8" />
    <title>SnacksDhaba</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

  </head>

  <body>
  <div class="container-fluid gx-0">
    <div class="d-flex align-items-center justify-content-between bg-primary py-3 px-4">
    <!-- Sidebar Toggle Button -->
    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar">☰</button>
    <h2 class="text-white m-0">SnacksDhaba</h2>

    <?php if (isset($_SESSION["admin_id"])) { ?>
      <div class="dropdown">
        <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo $_SESSION["admin_name"];?></button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="admin_profile_edit.php">My Profile</a></li>
          <li><a class="dropdown-item" href="changepassword.php">Change Password</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="main.php?flag=10" onclick="return confirm('Are you sure you want to logout??');">Logout</a></li>
        </ul>
      </div>
    <?php } 
    else{?>
      <div class="dropdown">
        <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          Profile
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="admin_login.php">LogIn</a></li>
        </ul>
      </div>
    <?php } ?>
  </div>
</div>


<div class="offcanvas offcanvas-start text-bg-dark" data-bs-backdrop="false" data-bs-scroll="true" tabindex="-1" id="offcanvasSidebar" bs-offcanvas-width= "0">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Menu</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="list-unstyled mb-2">
      <li><a href="dashboard.php" class="d-block px-3 py-2 bg-secondary text-white text-decoration-none rounded border border-white mb-2">Dashboard</a></li>
    </ul>

    <div class="accordion" id="accordionSidebar">
      <div class="accordion-item bg-dark border-0 mb-2">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed bg-secondary text-white border border-white rounded px-3 py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
            Product Category
          </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionSidebar">
          <div class="accordion-body">
            <ul class="list-unstyled">
              <li><a href="add_product_category.php" class="text-decoration-none text-white">Add Categories</a></li>
              <li><a href="category_table.php" class="text-decoration-none text-white">View Categories</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="accordion-item bg-dark border-0 mb-2">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed bg-secondary text-white border border-white rounded px-3 py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
            Products
          </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionSidebar">
          <div class="accordion-body">
            <ul class="list-unstyled">
              <li><a href="add_product.php" class="text-decoration-none text-white">Add Products</a></li>
              <li><a href="product_table.php" class="text-decoration-none text-white">View Products</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <ul class="list-unstyled mb-2">
      <li><a href="all_customer_table.php" class="d-block px-3 py-2 bg-secondary text-white text-decoration-none rounded border border-white mb-2">Customers</a></li>
    </ul>
    <div class="accordion" id="accordionSidebar">
      <div class="accordion-item bg-dark border-0 mb-2">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed bg-secondary text-white border border-white rounded px-3 py-2" type="button" data-bs-toggle="collapse" data-bs-target="#accordionthree">Order Management</button>
        </h2>
        <div id="accordionthree" data-bs-parent="#accordionSidebar" class="accordion-collapse collapse">
          <div class="accordion-body">
            <ul class="list-unstyled mb-2">
              <li><a href="all_order_table.php" class="text-white text-decoration-none">Orders</a></li>
              <li><a href="manage_order.php" class="text-white text-decoration-none">Process Orders</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>




    <ul class="list-unstyled mb-2">
      <li><a href="report.php" class="d-block px-3 py-2 bg-secondary text-white text-decoration-none rounded border border-white mb-2">Reports</a></li>
    </ul>
  </div>
</div>  