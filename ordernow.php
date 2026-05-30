<?php
include_once("admin/conn.php");
include_once("first.php"); 
include_once("nav_sidebar.php"); 

if (empty($_SESSION["cust_name"])) {
    if (isset($_POST['p_id'])) {
        $_SESSION['p_id'] = $_POST['p_id'];
    }
    header("Location: customer_login.php");
    exit;
}


$prod_id = null;
if (isset($_POST['p_id'])) {
    $prod_id = $_POST['p_id'];
    $_SESSION['p_id'] = $prod_id;
}elseif (isset($_SESSION['p_id'])) {
    $prod_id = $_SESSION['p_id'];
} else {
    die("No product selected.");
}


$sql = "SELECT p_id, product_name, product_price, product_image,product_description,product_weight_qty,product_weight_unit FROM product_detail WHERE p_id = '$prod_id'";
$result = mysqli_query($conn,$sql);

if (mysqli_num_rows($result) === 0) {
    die("Product not found.");
}
$product = mysqli_fetch_assoc($result);

$cust_id = $_SESSION["cust_id"];
$address_query = "select * from address_op where cust_id = '$cust_id'";
$address       = mysqli_query($conn,$address_query);

$prodCategory = "select pc_id from product_detail where p_id = '$prod_id'";
$prodCategory_result = mysqli_query($conn,$prodCategory);
$var = mysqli_fetch_assoc($prodCategory_result);
$pc_id = $var["pc_id"];
$recommend = "select * from product_detail where pc_id = '$pc_id' and p_id != '$prod_id'";
$recommend_result = mysqli_query($conn,$recommend);

?>
<div class="container-fluid">
<form method="POST" name="orderForm" id="orderForm" onsubmit="return validate()">
  <div class="container my-4">
    <div class="mb-3">
      <label for="dev_add" class="form-label fw-bold">Delivery Address:</label>
      <select name="delivery_address" class="form-select" id="dev_add">
        <?php if (mysqli_num_rows($address) == 0) { ?>
          <option value="">ADD A DELIVERY ADDRESS</option>
        <?php } ?>
        <?php while ($row = mysqli_fetch_assoc($address)) { ?>
          <option value="<?= $row['address_id']; ?>" <?= $row["is_default"] == 'T' ? 'selected' : '' ?>>
            <?= $row["name"] . " " . $row["pincode"] . " (" . $row["address_type"] . ")" ?>
          </option>
        <?php } ?>
      </select>
    </div>

    <!-- Card -->
    <div class="row align-items-center">
        <div class="col-12 d-flex justify-content-center">
            <div class="card border-2 border-dark shadow-sm h-100 w-100 ps-2 pe-2">

                <div class="row align-items-center my-2">
                    <div class="col-auto d-flex">
                        <img src="<?= htmlspecialchars($product['product_image']) ?>" alt="Product" class="image-fluid rounded" style="max-width: 100%; height: 25vh;">
                    </div>
                
                    <div class="col qty-selector d-flex justify-content-end flex-nowrap gap-2">
                        
                        <button type="button" id="btnMinus" class="btn btn-outline-secondary d-flex justify-content-center align-items-center p-0 mt-2" style="width:100%; max-width: 50px; max-height: 50px; height:100%;"><i class="bi bi-dash-lg"></i></button>
                        <input type="text" id="order_qty" name="order_qty" value="1" readonly class="form-control text-center mx-2 qty-input" style="max-width: 60px;">
                        <button type="button" id="btnPlus" class="btn btn-outline-secondary d-flex justify-content-center align-items-center p-0 mt-2" style="width:100%; max-width: 50px; max-height: 50px; height:100%;"><i class="bi bi-plus-lg"></i></button>
                        
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col"><h4 class="m-2"><?= htmlspecialchars($product['product_name']) ?></h4>
                    </div>
                    <div class="col text-end">
                        <h6 class="m-2">₹<span id="unit_price"><?= number_format((float)$product['product_price'], 2) ?></span> per <span> <?= htmlspecialchars($product['product_weight_qty'] . $product['product_weight_unit']) ?></span>
                        </h6>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col"><p class="m-2"><span class="fw-bold">DESCRIPTION: </span><?= htmlspecialchars($product['product_description']) ?></p>
                    </div>
                </div>
                
                <hr>
                <div class="row mb-2">
                    <div class="col mx-4"><h5>Total:</h5></div>
                    <div class="col text-end mx-4"><h5>₹<span id="total_price"><?= number_format($product['product_price'], 2) ?></span></h5></div>
                </div>

                <div class="row mb-2">
                    <div class="col-12">
                        <input type="hidden" name="prod_id" value="<?= $product['p_id'] ?>" />
                        <input type="hidden" name="cust_id" value="<?= $_SESSION['cust_id'] ?>" />
                        <input type="hidden" name="prod_price" id="prod_price" value="<?= $product['product_price'] ?>" />
                        
                        <!-- Checkout button -->
                        <button type="submit" class="btn btn-warning w-100">Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
        
<script>
    function validate(){
        const address = orderForm.delivery_address.value;
        if(address == ""){
            alert("Add a delivery address");
            window.location.href = "address.php";          
            return false;
        }
        orderForm.action= "paynow.php";    //"admin/main.php?flag=15";
    }

    const btnPlus = document.getElementById('btnPlus');
    const btnMinus = document.getElementById('btnMinus');
    const qtyInput = document.getElementById('order_qty');
    const unitPrice = parseFloat(document.getElementById('prod_price').value);
    const totalPriceEl = document.getElementById('total_price');

    function updateTotal() {
        const qty = parseInt(qtyInput.value);
        const total = (qty * unitPrice).toFixed(2);
        totalPriceEl.textContent = total;
    }

    btnPlus.addEventListener('click', () => {
        qtyInput.value = parseInt(qtyInput.value) + 1;
        updateTotal();
    });

    btnMinus.addEventListener('click', () => {
        const currentQty = parseInt(qtyInput.value);
        if (currentQty > 1) {
            qtyInput.value = currentQty - 1;
            updateTotal();
        }
    });
</script>
<?php include_once("footer.php"); ?>