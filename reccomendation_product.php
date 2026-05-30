<?php
include("admin/conn.php");
include_once("first.php"); 
include_once("nav_sidebar.php");

$cust_id = $_SESSION["cust_id"];
$order_query = "SELECT t1.prod_id FROM suborder_op t1 INNER JOIN main_order_table t2 ON t1.order_id = t2.order_id WHERE t2.cust_id = $cust_id";
$cart_query  = "SELECT p_id FROM cart WHERE cust_id = $cust_id";

$order_result = mysqli_query($conn, $order_query);
$cart_result  = mysqli_query($conn, $cart_query);

$product_ids = [];

while ($row = mysqli_fetch_assoc($order_result)) {
    $product_ids[] = $row['prod_id'];
}
while ($row = mysqli_fetch_assoc($cart_result)) {
    $product_ids[] = $row['p_id'];
}

// Remove duplicates
$product_ids = array_unique($product_ids);

// Get recommendations from Python
$recommendations = [];
if (!empty($product_ids)) {
    $ids_str = implode(',', $product_ids);
    $python = "E:\\NLP Recommendation\\venv\\Scripts\\python.exe";
    $script = "C:\\xampp\\htdocs\\online_portal\\tf_idf_Products.py";

    $command = "\"$python\" \"$script\" \"$ids_str\"";
    $output = shell_exec($command);
    $recommendations = json_decode($output, true);

}
?>

<div class="container-fluid">
    <h2 class="page-title m-4">Recommended For You</h2>

    <div class="row recommend-grid">
        <?php if (!empty($recommendations)): ?>
            <?php foreach ($recommendations as $rec): ?>
                <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-center mb-4 recommend-item">
                    <div class="card h-100 w-100">

                        <!-- Product Image -->
                        <img src="<?php echo $rec['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($rec['name']); ?>">

                        <!-- Card Body -->
                        <div class="card-body d-flex flex-column">

                            <h5 class="card-title text-primary responsive-font">
                                <?php echo htmlspecialchars($rec['name']); ?>
                            </h5>

                            <?php if (!empty($rec['category'])): ?>
                              <p class="text-success mb-2 responsive-font"><?php echo htmlspecialchars($rec['category']); ?></p>
                            <?php endif; ?>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                              <!-- Price -->
                              <p class="mb-0 fw-bold text-danger"><i class="bi bi-currency-rupee"></i>
                                <?php echo number_format($rec["price"], 2); ?>
                              </p>
                              <!-- Weight/Unit -->
                              <?php if (!empty($rec["weight"]) && !empty($rec["unit"])): ?>
                              <p class="mb-0 text-dark fw-bold fs-5"><?php echo htmlspecialchars($rec["weight"] . ' ' . $rec["unit"]); ?></p>
                              <?php endif; ?>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-auto d-flex gap-2">
                                <form method="POST" action="ordernow.php" class="flex-fill">
                                    <input type="hidden" name="p_id" value="<?php echo $rec['p_id']; ?>">
                                    <button type="submit" class="btn btn-warning btn-sm w-100 btn-rec">Order Now</button>
                                </form>

                                <form method="POST" action="admin/main.php?flag=23" class="flex-fill">
                                    <input type="hidden" name="p_id" value="<?php echo $rec['p_id']; ?>">
                                    <button type="submit" class="btn btn-primary btn-sm w-100 btn-rec" name="cart_btn">Add to Cart</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center fs-5 mt-4">No recommendations available yet.</p>
        <?php endif; ?>
    </div>
</div>
<?php include_once("footer.php"); ?>