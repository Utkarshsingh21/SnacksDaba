<?php
include_once("conn.php");
include_once("start.php");

#categoy
$sql2 = "select pc_id,product_category_name from product_category";
$result2 = mysqli_query($conn,$sql2);

if(isset($_POST["searchbtn"])){
  $product_name = $_POST['product_name'];
  $order_status = $_POST['order_status'];
  $category = $_POST['category'];

  if(!empty($_POST["from_date"])){
    $x = new DateTime($_POST["from_date"]);
    $from_date = $x->format("Y-m-d");
  }
  else{
    $from_date = $_POST["from_date"];
  }

  if(!empty($_POST["to_date"])){
    $y = new DateTime($_POST["to_date"]);
    $to_date = $y->format("Y-m-d");
  }
  else{
    $to_date = $_POST["to_date"];
  }
   
  $order_query = "SELECT t1.*,t2.address_type,t2.pincode,t2.address,t3.cust_name,t3.cust_email,t3.cust_mobno FROM main_order_table t1 inner join address_op t2 on t1.delivery_address = t2.address_id inner join customer_op t3 on t1.cust_id = t3.cust_id where t1.order_status like '%$order_status%' and (('$from_date' = '' OR t1.order_dtime >= '$from_date')
  AND
    ('$to_date' = '' OR t1.order_dtime < DATE_ADD('$to_date', INTERVAL 1 DAY)))order by t1.order_dtime desc";

  $orders = mysqli_query($conn,$order_query);

    $suborders_query = "select t1.*,t2.product_name from suborder_op t1 inner join product_detail t2 on t1.prod_id = t2.p_id inner join product_category t3 on t3.pc_id = t2.pc_id where t3.pc_id like '%$category%' and t2.product_name like '%$product_name%' and t1.order_status like '%$order_status%' and (('$from_date' = '' OR t1.order_dtime >= '$from_date')
  AND
    ('$to_date' = '' OR t1.order_dtime < DATE_ADD('$to_date', INTERVAL 1 DAY)))order by t1.order_dtime desc";

    $suborders_result = mysqli_query($conn,$suborders_query);

    $suborders = [];
    while ($row = mysqli_fetch_assoc($suborders_result)) {
      $suborders[$row['order_id']][] = $row;
    }

}

 else{
   
  $order_query = "select t1.*,t2.cust_name,t2.cust_email,t2.cust_mobno,t3.address,t3.pincode,t3.address_type from main_order_table t1 inner join customer_op t2 on t1.cust_id = t2.cust_id inner join address_op t3 on t1.delivery_address = t3.address_id order by t1.order_dtime desc";
  $orders = mysqli_query($conn,$order_query);


  $suborders_query = "SELECT t1.*,t2.product_name FROM suborder_op t1 inner join product_detail t2 on t1.prod_id = t2.p_id order by t1.order_dtime desc";
  $suborders_result = mysqli_query($conn,$suborders_query);

  $suborders = [];
  while ($row = mysqli_fetch_assoc($suborders_result)) {
    $suborders[$row['order_id']][] = $row;
  }
 }
?>


<link rel="stylesheet" href="sidebar_style.css">
<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="cancelOrderForm" method="POST" action="main.php?flag=22">
      <input type="hidden" name="order_id" id="cancelOrderId" />
      <input type="hidden" name="sub_order_id" id="cancelsubOrderId" />
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cancelOrderModalLabel">Cancel Order</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to cancel this order?</p>
          <div class="mb-3">
            <label for="cancelReason" class="form-label">Reason for cancellation:</label>
            <textarea class="form-control" name="reason" id="cancelReason" rows="3" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger w-100">Yes, Cancel Order</button>
        </div>
      </div>
    </form>
  </div>
</div>

<main class="main-content mt-2" id="mainContent">

  <form method="post" class="container border rounded bg-white shadow-sm p-4 mb-2">
<div class="row mb-3">
  <!-- Product Category -->
  <div class="col-12 col-sm-6 col-md-3 mb-3">
    <label for="category" class="form-label fw-semibold">Product Category</label>
    <select name="category" id="category" class="form-select">
      <option value="">ALL CATEGORY</option>
      <?php while($row = mysqli_fetch_assoc($result2)){ ?>
        <option value="<?php echo $row["pc_id"]; ?>" <?php if (isset($_POST["category"]) && $_POST["category"] == $row["pc_id"]) echo "selected"; ?>>
          <?php echo htmlspecialchars($row["product_category_name"]); ?>
        </option>
      <?php } ?>
    </select>
  </div>

  <!-- Order Status -->
  <div class="col-12 col-sm-6 col-md-3 mb-3">
    <label for="order_status" class="form-label fw-semibold">Order Status</label>
    <select name="order_status" id="order_status" class="form-select">
      <option value="">ALL</option>
      <option value="P" <?php if(isset($_POST["order_status"]) && $_POST["order_status"] == "P") echo "selected"; ?>>PLACED</option>
      <option value="D" <?php if(isset($_POST["order_status"]) && $_POST["order_status"] == "D") echo "selected"; ?>>DELIVERED</option>
      <option value="C" <?php if(isset($_POST["order_status"]) && $_POST["order_status"] == "C") echo "selected"; ?>>CANCELED</option>
    </select>
  </div>

  <!-- Order Start Date -->
  <div class="col-12 col-sm-6 col-md-3 mb-3">
    <label for="start_date" class="form-label fw-semibold">Order Start Date</label>
    <input type="text" name="from_date" id="start_date" class="form-control" autocomplete="off"
      value="<?php if(isset($_POST['from_date'])) echo htmlspecialchars($_POST['from_date']); ?>">
  </div>

  <!-- Order End Date -->
  <div class="col-12 col-sm-6 col-md-3 mb-3">
    <label for="end_date" class="form-label fw-semibold">Order End Date</label>
    <input type="text" name="to_date" id="end_date" class="form-control" autocomplete="off"
      value="<?php if(isset($_POST['to_date'])) echo htmlspecialchars($_POST['to_date']); ?>">
  </div>
</div>

<div class="row mb-4">
  <!-- Product Name -->
  <div class="col-12 col-md-12 col-lg-12 mb-3">
    <label for="product_name" class="form-label fw-semibold">Product Name</label>
    <input type="search" name="product_name" id="product_name" class="form-control" placeholder="Product Name"
      value="<?php if (isset($_POST['product_name'])) echo htmlspecialchars($_POST['product_name']); ?>">
  </div>
</div>

<!-- Fetch Button -->
<div class="row">
  <div class="col-12 d-flex justify-content-center">
    <button type="submit" name="searchbtn" class="btn btn-primary px-5 py-2 fw-semibold">Fetch</button>
  </div>
</div>

</form>

  <?php if(mysqli_num_rows($orders) == 0 || mysqli_num_rows($suborders_result) == 0) { ?>
    <h3 class="text-center fs-4 fw-bold">No orders found matching the given details.</h3>
  <?php } else { ?> 
    <h1 class="text-dark text-center text-decoration-underline fs-3 fw-bold m-3">ORDER LIST</h1>

  <div class="table-responsive shadow-sm" style="max-width:90%; margin: auto;">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Customer Name</th>
          <th>Contact Info</th>
          <th>Order Total</th>
          <th>Delivery Address</th>
          <th>Order Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($order = $orders->fetch_assoc()): 
          $className = ($order["order_status"] == 'P') ? "table-success" : 
                       (($order["order_status"] == 'C') ? "table-danger" : 
                       (($order["order_status"] == 'S') ? "table-info" : 
                       (($order["order_status"] == 'D') ? "table-success" : ""))); ?>
          <tr class="clickable-row <?php echo $className; ?>" data-bs-toggle="collapse" data-bs-target="#suborder-<?php echo $order['order_id']; ?>" aria-expanded="false" aria-controls="suborder-<?php echo $order['order_id']; ?>">
            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
            <td><?php echo htmlspecialchars($order['cust_name']); ?></td>
            <td class="w-25">
              <div><?php echo "<i class='bi bi-envelope fs-5'></i>->".htmlspecialchars($order['cust_email']); ?></div>
              <div><i class="bi bi-telephone fs-5"></i>-><?php echo htmlspecialchars($order['cust_mobno']); ?></div>
            </td>
            <td>₹<?php echo number_format($order['order_amount'], 2); ?></td>
            <td>
              <div><?php echo htmlspecialchars($order['address_type']); ?>: <?php echo htmlspecialchars($order['address']); ?></div>
              <div>Pincode: <?php echo htmlspecialchars($order['pincode']); ?></div>
            </td>
            <td>
              <?php 
                $statusMap = ['P' => 'Placed', 'D' => 'Delivered', 'C' => 'Canceled', 'S' => 'Shipped'];
                echo $statusMap[$order["order_status"]] ?? $order["order_status"];
              ?>
            </td>
            <td>
              <?php if ($order["order_status"] !== 'C' && $order["order_status"] !== 'D') : ?>
                <i class="bi bi-x-circle icon fs-4 text-danger m-4" title="Cancel Order" data-bs-toggle="tooltip" data-bs-placement="top" role="button" 
                   onclick="openCancelModal('<?php echo $order['order_id']; ?>', '0'); event.stopPropagation();"></i>
                <a href="manage_order.php?order_id=<?php echo $order['order_id'];?>" title="Change Status"><i class="bi bi-pencil fs-4"></i></a> 
              <?php else: ?>
                <span class="text-muted" title="No action available" data-bs-toggle="tooltip" data-bs-placement="top">
                  <i class="bi bi-lock-fill fs-4 text-dark m-4"></i>
                </span>
              <?php endif; ?>
            </td>
          </tr>

          <!-- Suborder rows, collapsible -->
          <tr class="collapse suborder-table bg-light" id="suborder-<?php echo $order['order_id']; ?>">
            <td colspan="7" class="p-0">
              <table class="table mb-0">
                <thead class="table-secondary">
                  <tr>
                    <th>Suborder ID</th>
                    <th>Product Name</th>
                    <th>Qty</th>
                    <th>Suborder Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(isset($suborders[$order['order_id']])): ?>
                    <?php foreach ($suborders[$order['order_id']] as $sub): ?>
                      <tr>
                        <td><?php echo htmlspecialchars($sub['sub_order_id']); ?></td>
                        <td><?php echo htmlspecialchars($sub['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($sub['order_qty']); ?></td>
                        <td>
                          <?php
                            $subStatusMap = ['P' => 'Placed', 'D' => 'Delivered', 'C' => 'Canceled', 'S' => 'Shipped'];
                            echo $subStatusMap[$sub["order_status"]] ?? $sub["order_status"];
                          ?>
                        </td>
                        <td>
                          <?php if($sub['order_status'] == 'P'): ?>
                            <i class="bi bi-x-circle icon" title="Cancel Suborder" data-bs-toggle="tooltip" data-bs-placement="top" role="button"
                               onclick="openCancelModal('<?php echo $order['order_id']; ?>', '<?php echo $sub['sub_order_id']; ?>'); event.stopPropagation();"></i>
                          <?php else: ?>
                            <span class="text-muted" title="No action available" data-bs-toggle="tooltip" data-bs-placement="top">
                              <i class="bi bi-lock-fill"></i>
                            </span>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr><td colspan="5" class="text-center text-muted">No suborders found.</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </td>
          </tr>

        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <?php } ?>

</main>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Initialize datepickers
  $( function() {
    $("#start_date, #end_date").datepicker({ dateFormat: 'yy-mm-dd' });
  });

  // Bootstrap tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Open cancel modal and fill hidden inputs
  function openCancelModal(orderId, subOrderId) {
    $("#cancelOrderId").val(orderId);
    $("#cancelsubOrderId").val(subOrderId);
    var modal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
    modal.show();
  }

  // Prevent row collapse toggle when clicking action icon
  $(".icon").click(function(e){
    e.stopPropagation();
  });
</script>
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

<!-- Bootstrap JS and dependencies -->

</body>
</html>