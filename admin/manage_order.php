<?php
include_once("conn.php");
include_once("start.php");

// Initialize variables
$orders = [];
$suborders = [];
$pre_status = null;

// Fetch orders by GET or POST
if (isset($_GET["order_id"]) && !empty($_GET["order_id"])) {
    $order_id = $_GET["order_id"];
} elseif (isset($_POST["fetchbtn"])) {
    $order_id = $_POST["order_id"];
}

// Only run queries if $order_id is set
if (!empty($order_id)) {
    // Main order query
    $order_query = "SELECT t1.*, t2.address_type, t2.pincode, t2.address, t3.cust_name, t3.cust_email, t3.cust_mobno 
                    FROM main_order_table t1
                    INNER JOIN address_op t2 ON t1.delivery_address = t2.address_id
                    INNER JOIN customer_op t3 ON t1.cust_id = t3.cust_id
                    WHERE t1.order_id LIKE '%$order_id%'";
    $orders = mysqli_query($conn, $order_query);

    // Fetch the first row to check previous status (for POST)
    if (isset($_POST["fetchbtn"]) && mysqli_num_rows($orders) > 0) {
        $sub = mysqli_fetch_assoc($orders);
        $pre_status = $sub["order_status"];
        // Reset pointer to start
        mysqli_data_seek($orders, 0);
    }

    // Suborder query
    $suborders_query = "SELECT t1.*, t2.product_name 
                        FROM suborder_op t1
                        INNER JOIN product_detail t2 ON t1.prod_id = t2.p_id
                        ORDER BY t1.order_dtime DESC";
    $suborders_result = mysqli_query($conn, $suborders_query);

    $suborders = [];
    while ($row = mysqli_fetch_assoc($suborders_result)) {
        $suborders[$row['order_id']][] = $row;
    }
}
?>

<link rel="stylesheet" href="sidebar_style.css">

<main class="main-content px-3" id="mainContent">

<!-- Alerts -->
<?php if(isset($_GET["msg"]) && $_GET["msg"] == 1): ?>
    <div class="alert alert-primary alert-dismissible fade show mt-2" role="alert">
        Order Status was Successfully Changed.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif(isset($_GET["err"]) && $_GET["err"] == 1): ?>
    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
        Order Status was not Changed.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Search Form -->
<form method="post" class="d-flex flex-column flex-md-row justify-content-center align-items-center border rounded border-dark bg-light mt-4 mb-3 p-3" style="min-height: 100px;">
    <label for="order_id" class="form-label h5 me-md-2 mb-2 mb-md-0">Order Id:</label>
    <input type="search" id="order_id" name="order_id" class="form-control me-md-2 mb-2 mb-md-0" placeholder="Enter Order Id"
           value="<?= isset($order_id) ? $order_id : '' ?>" style="max-width: 100vh;">
    <input type="submit" name="fetchbtn" value="FETCH" class="btn btn-primary">
</form>

<?php if (!empty($order_id)): ?>
    <?php if (mysqli_num_rows($orders) == 0): ?>
        <h3 class="text-center">NO ORDERS WERE FOUND MATCHING GIVEN DETAILS.</h3><hr>
    <?php elseif (isset($pre_status) && ($pre_status == 'C' || $pre_status == 'D')): ?>
        <h3 class="text-center">ORDER STATUS CANNOT BE CHANGED.</h3><hr>
    <?php else: ?>
        <!-- Orders Table -->
        <div class="table-responsive m-4">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="thead-light">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Customer Contact Info</th>
                        <th>Order Total</th>
                        <th>Delivery Address</th>
                        <th>Order Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($order = mysqli_fetch_assoc($orders)): ?>
                    <?php
                    // Determine row class
                    switch ($order["order_status"]) {
                        case 'P': $className = "table-success"; break;
                        case 'C': $className = "table-danger"; break;
                        case 'S': $className = "table-info"; break;
                        case 'D': $className = "table-success"; break;
                        default: $className = ""; break;
                    }

                    // Determine order status text
                    switch ($order["order_status"]) {
                        case 'P': $statusText = "Placed"; break;
                        case 'C': $statusText = "Cancelled"; break;
                        case 'S': $statusText = "Shipped"; break;
                        case 'D': $statusText = "Delivered"; break;
                        default: $statusText = ""; break;
                    }
                    ?>
                    <tr class="clickable-row <?= $className ?>" data-bs-toggle="collapse" data-bs-target="#suborder-<?= $order['order_id'] ?>">
                        <td><?= $order['order_id'] ?></td>
                        <td><?= $order["cust_name"] ?></td>
                        <td>
                            <i class="bi bi-envelope-at-fill"></i> -> <?= $order["cust_email"] ?><br>
                            <i class="bi bi-telephone-fill"></i> -> <?= $order["cust_mobno"] ?>
                        </td>
                        <td><i class="bi bi-currency-rupee"></i><?= number_format($order['order_amount'], 2) ?></td>
                        <td><?= $order["address"] . " " . $order["pincode"] . " (" . $order["address_type"] . ")" ?></td>
                        <td><?= $statusText ?></td>
                    </tr>
                    <tr id="suborder-<?= $order['order_id'] ?>" class="show">
                        <td colspan="6" class="p-0">
                            <?php if (!empty($suborders[$order['order_id']])): ?>
                                <table class="table table-sm mb-0 suborder-table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Unit Price</th>
                                            <th>Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($suborders[$order['order_id']] as $sub):
                                        $subClass = ($sub["order_status"] == 'P') ? "table-warning" : 
                                                    (($sub["order_status"] == 'C') ? "table-danger" : "table-info"); ?>
                                        <tr class="<?= $subClass ?>">
                                            <td><?= $sub['product_name'] ?></td>
                                            <td><?= $sub['order_qty'] ?></td>
                                            <td><i class="bi bi-currency-rupee"></i><?= number_format($sub['order_price'], 2) ?></td>
                                            <td><i class="bi bi-currency-rupee"></i><?= number_format($sub['order_tamount'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="p-2">No suborders found.</div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Change Status Form -->
        <div class="d-flex justify-content-center mt-4">
            <form action="main.php?flag=30" method="POST" class="d-flex flex-column flex-md-row justify-content-center align-items-center border rounded border-dark bg-light mt-4 mb-3 p-3" style="min-height: 100px; max-width: 100%;">
                <label class="form-label h6 me-md-4 mb-2 mb-md-0 text-center text-md-start">Change Order Status To:</label>
                <select name="order_status" class="form-control w-100 w-md-auto me-md-2 mb-2 mb-md-0" style="max-width: 250px;">
                    <option value="S">In-Progress || Shipped</option>
                    <option value="D">Delivered</option>
                </select>
                <input type="hidden" name="order_id" value="<?= $order_id ?>">
                <input type="submit" name="changebtn" value="Change" class="btn btn-dark w-100 w-md-auto">
            </form>
        </div>
    <?php endif; ?>
<?php endif; ?>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include_once("end.php"); ?>
</body>
</html>
