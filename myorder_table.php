<?php
include("admin/conn.php");
include_once("first.php"); 
include_once("nav_sidebar.php");

if (!isset($_SESSION["cust_id"])) {
    echo "<p class='text-danger text-center mt-5'>You must be logged in to view your orders.</p>";
    include("footer.php");
    exit;
}

$cust_id = $_SESSION['cust_id'];

$order_query = "SELECT t1.*, t2.address 
FROM main_order_table t1 
INNER JOIN address_op t2 ON t1.delivery_address = t2.address_id 
WHERE t1.cust_id = '$cust_id' 
ORDER BY t1.order_dtime DESC";

$orders = mysqli_query($conn, $order_query);
?>

<div class="container mt-4 mb-5">

  <!-- LEFT ALIGNED TITLE -->
  <h3 class="page-title mb-4">My Orders</h3>

  <?php if (mysqli_num_rows($orders) == 0) { ?>
      <p class="text-muted">No orders found</p>
  <?php } else { ?>

  <?php while ($order = mysqli_fetch_assoc($orders)) { ?>

  <?php
  $status = $order["order_status"];
  $status_text = "";
  $status_class = "";

  if ($status == 'P') { $status_text = "Placed"; $status_class = "placed"; }
  elseif ($status == 'S') { $status_text = "Shipped"; $status_class = "shipped"; }
  elseif ($status == 'D') { $status_text = "Delivered"; $status_class = "delivered"; }
  elseif ($status == 'C') { $status_text = "Cancelled"; $status_class = "cancelled"; }
  ?>

  <div class="order-card">

    <!-- HEADER -->
    <div class="order-header">
      <div>
        <strong>Order #<?= $order['order_id'] ?></strong><br>
        <small class="text-muted"><?= $order['order_dtime'] ?></small>
      </div>

      <span class="badge-status <?= $status_class ?>"><?= $status_text ?></span>
    </div>

<!-- TOTAL -->
<div class="mt-2 mb-2">
    <strong>Total:</strong> ₹<?= $order['order_amount'] ?>
</div>

<!-- ADDRESS -->
<div class="text-muted mb-2">
    <?= $order['address'] ?>
</div>

<!-- PRODUCTS -->
<?php
$oid = $order['order_id'];

$sub_query = "SELECT t1.*, t2.product_name, t2.product_image 
              FROM suborder_op t1
              JOIN product_detail t2 ON t1.prod_id = t2.p_id
              WHERE t1.order_id = '$oid'";

$subs = mysqli_query($conn, $sub_query);
?>

<?php while ($sub = mysqli_fetch_assoc($subs)) { ?>
<div class="product-row">

    <div class="product-info">
        <!-- PRODUCT IMAGE -->
        <img src="<?= $sub['product_image'] ?>" class="order-product-img">

        <div>
            <div><?= $sub['product_name'] ?></div>
            <small class="text-muted">Qty: <?= $sub['order_qty'] ?></small>
        </div>
    </div>

    <div>
        ₹<?= $sub['order_tamount'] ?>
    </div>

</div>
<?php } ?>

<!-- ACTION -->
<div class="text-end mt-3">
<?php if ($status != 'C' && $status != 'D') { ?>
    <a href="admin/main.php?flag=16&order_id=<?= $order['order_id'] ?>" 
       class="btn btn-sm btn-outline-danger">
       Cancel Order
    </a>
<?php } ?>
</div>

</div>

<?php } ?>
<?php } ?>

</div>

<?php include("footer.php"); ?>