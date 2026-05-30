<?php
include_once("conn.php");
include_once("start.php");

$sql = "SELECT
  COUNT(*) AS total_categories,
  SUM(CASE WHEN product_category_status = 'T' THEN 1 ELSE 0 END) AS true_categories
  FROM product_category";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$total_categories = $row["total_categories"];

$sql = "SELECT
  COUNT(*) AS total_products,
  SUM(CASE WHEN product_status = 'T' THEN 1 ELSE 0 END) AS true_products
FROM product_detail";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$total_products = $row["total_products"];

$sql = "SELECT
  COUNT(*) AS total_orders,
  SUM(CASE WHEN order_status = 'D' THEN 1 ELSE 0 END) AS d_orders,
  SUM(CASE WHEN order_status = 'C' THEN 1 ELSE 0 END) AS c_orders,
  SUM(CASE WHEN order_status = 'S' THEN 1 ELSE 0 END) AS s_orders
FROM main_order_table";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$total_orders = $row["total_orders"];
$d_orders = $row["d_orders"];
$c_orders = $row["c_orders"];
$s_orders = $row["s_orders"];
?>
<link rel="stylesheet" href="style.css">
<style>
main.main-dash {
    padding: 2rem 1rem;
    min-height: 89vh;
    background: #f9fafb;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  .card {
    border-radius: 0.75rem;
    box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.1);
    transition: transform 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  }
  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
  }
  .card-title {
    font-weight: 700;
    font-size: 1.25rem;
  }
  .card-text {
    font-size: 2.5rem;
    font-weight: 800;
  }
  .dashboard-row {
    gap: 2rem;
  }
  @media (max-width: 576px) {
    .card-text {
      font-size: 2rem;
    }
    .card-title {
      font-size: 1.5rem;
    }
  }
  .mydisplay{
    display: flex;
  }
  .mywidth{
    width: 100%;
  }</style>
<main class="main-dash" id="mainContent">
  <div class="container">

    <?php if (isset($_GET["msg"]) && $_GET["msg"] == 100): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        Operation was Successful
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <h1 class="mb-4 text-center text-primary fw-bold">Dashboard Overview</h1>

    <div class="row" style="margin-top: 50px;" id="dashboard">

      <div class="col-12 col-sm-6 col-md-4 mb-4 d-flex">
        <div class="card text-primary bg-white p-3  h-100 w-100 d-flex flex-column">
          <div class="card-body text-center">
            <h2 class="card-title">Total Categories</h2>
            <p class="card-text"><?= $total_categories ?></p>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-4 mb-4 d-flex">
        <div class="card text-warning bg-white p-3  h-100 w-100 d-flex flex-column">
          <div class="card-body text-center">
            <h2 class="card-title">Total Products</h2>
            <p class="card-text"><?= $total_products ?></p>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-4 mb-4 d-flex">
        <div class="card text-success bg-white p-3  h-100 w-100 d-flex flex-column">
          <div class="card-body text-center">
            <h2 class="card-title">Total Orders</h2>
            <p class="card-text"><?= $total_orders ?></p>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-4 mb-4 d-flex">
        <div class="card text-danger bg-white p-3  h-100 w-100 d-flex flex-column">
          <div class="card-body text-center">
            <h2 class="card-title">Delivered Orders</h2>
            <p class="card-text"><?= $d_orders ?></p>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-4 mb-4 d-flex">
        <div class="card text-secondary bg-white p-3 h-100 w-100 d-flex flex-column">
          <div class="card-body text-center">
            <h2 class="card-title">Shipped Orders</h2>
            <p class="card-text"><?= $s_orders ?></p>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-4 mb-4 d-flex">
        <div class="card text-muted bg-white p-3  h-100 w-100 d-flex flex-column">
          <div class="card-body text-center">
            <h2 class="card-title">Cancelled Orders</h2>
            <p class="card-text"><?= $c_orders ?></p>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  window.addEventListener('DOMContentLoaded', function () {
    const sidebarElement = document.getElementById('offcanvasSidebar');
    const mainContent = document.getElementById('mainContent');
    const sidebar = new bootstrap.Offcanvas(sidebarElement);

    const SIDEBAR_WIDTH = 230;
    const BREAKPOINT = 768;

    function handleSidebarDisplay() {
      if (window.innerWidth >= BREAKPOINT) {
        sidebar.show();
        mainContent.style.marginLeft = `${SIDEBAR_WIDTH}px`;
      } else {
        sidebar.hide();
        mainContent.style.marginLeft = 0;
      }
    }

    // Initial run
    handleSidebarDisplay();

    // Rerun on resize
    window.addEventListener('resize', handleSidebarDisplay);

    // Listen to offcanvas events to update layout
    sidebarElement.addEventListener('shown.bs.offcanvas', function () {
      if (window.innerWidth >= BREAKPOINT) {
        mainContent.style.marginLeft = `${SIDEBAR_WIDTH}px`;
      }
    });

    sidebarElement.addEventListener('hidden.bs.offcanvas', function () {
      mainContent.style.marginLeft = 0;
    });
  });
</script>
</body>
</html>