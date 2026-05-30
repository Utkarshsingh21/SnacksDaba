<?php
include("admin/conn.php");
include_once("first.php"); 
include_once("nav_sidebar.php"); 

if (empty($_SESSION["cust_name"])) {
    if (isset($_POST['p_id'])) {
        $_SESSION['p_id'] = $_POST['p_id'];
    }
    header("Location: customer_login.php");
    exit;
}

$cust_id = $_SESSION["cust_id"];

$sql = "SELECT t1.p_id,t1.product_name, t1.product_price, t1.product_image FROM product_detail t1 inner join cart t2 on t1.p_id = t2.p_id where t2.cart_flag = 'F' and cust_id = '$cust_id'";
$result = mysqli_query($conn,$sql);


$address_query = "select * from address_op where cust_id = '$cust_id'";
$address       = mysqli_query($conn,$address_query);
?>

<div class="container-fluid" id="main-content">
 <?php if (mysqli_num_rows($result) === 0) { ?>
  <h3 class="text-center my-4">NO PRODUCTS WERE ADDED TO CART</h3>
  <hr>
<?php } else { ?>
<form method="POST" name="orderForm" id="orderForm" onsubmit="return validate()">
  <div class="container my-4">
    <!-- Delivery Address -->
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

    <!-- Cart Items -->
    <div class="row g-3">
      <?php while ($product = mysqli_fetch_assoc($result)) { ?>
        <div class="col-12">
          <div class="card border-dark shadow-sm p-3 w-100">
            <!-- Remove Button -->
            <div class="d-flex justify-content-end">
              <a href="admin/main.php?flag=25&val=<?= $product["p_id"] ?>" class="btn btn-sm btn-danger">
                Remove From Cart
              </a>
            </div>

            <!-- Row 1: Checkbox + Image + Qty -->
            <div class="row align-items-center my-3">
              <div class="col-auto">
                <input type="checkbox" name="prod_selection[]" class="form-check-input prod_selection" value="<?= $product['p_id'] ?>">
                <input type="hidden" name="prod_price[<?= $product['p_id'] ?>]" class="prod_price" value="<?= $product['product_price'] ?>">
              </div>
              <div class="col-auto">
                <img src="<?= htmlspecialchars($product['product_image']) ?>" alt="Product" class="img-fluid rounded" style="max-width: 80px; max-height: 80px;">
              </div>
              <div class="col ms-auto qty-selector d-flex align-items-center justify-content-end flex-nowrap">
                <button type="button" class="btn btn-outline-secondary btnMinus me-2">
                  <i class="bi bi-dash-lg"></i>
                </button>
                <input type="text" name="order_qty[<?= $product['p_id'] ?>]" value="1" readonly class="form-control text-center order_qty mx-1" style="width: 60px;">
                <button type="button" class="btn btn-outline-secondary btnplus ms-2">
                  <i class="bi bi-plus-lg"></i>
                </button>
              </div>
            </div>

            <!-- Row 2: Product Name & Unit Price -->
            <div class="row mb-2">
              <div class="col"><h5 class="mb-0"><?= htmlspecialchars($product['product_name']) ?></h5></div>
              <div class="col text-end"><h5 class="mb-0">₹<span class="unit_price"><?= $product['product_price'] ?></span></h5></div>
            </div>

            <!-- Row 3: Total -->
            <hr>
            <div class="row mb-2">
              <div class="col"><h5>Total:</h5></div>
              <div class="col text-end"><h5>₹<span class="total_price"><?= $product['product_price'] ?></span></h5></div>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>

    <!-- Grand Total + Checkout -->
    <hr>
    <div id="alltotal" class="mt-4" style="display: none;">
      <div class="d-flex justify-content-between mb-3">
        <h3>Grand Total</h3>
        <h3 id="grandtotal"></h3>
      </div>
      <input type="hidden" name="cust_id" value="<?= $_SESSION['cust_id'] ?>">
      <button type="submit" class="btn btn-warning btn-lg w-100">Checkout</button>
    </div>
  </div>
</form>
<?php } ?>
</div>

<script>
function validate(){
    
    const address = orderForm.delivery_address.value;

    if(address == ""){
        alert("Add a delivery address");
        window.location.href = "address.php";
        return false;

    }
    orderForm.action= "cart_ordernow.php";        //"admin/main.php?flag=24";
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btnplus').forEach(button => {
        button.addEventListener('click', () => {
            const card = button.closest('.card');
            const qtyInput = card.querySelector('.order_qty');
            let qty = parseInt(qtyInput.value);
            qty++;
            qtyInput.value = qty;

            updateTotal(card);
            updateGrandTotal();
        });
    });

    document.querySelectorAll('.btnMinus').forEach(button => {
        button.addEventListener('click', () => {
            const card = button.closest('.card');
            const qtyInput = card.querySelector('.order_qty');
            let qty = parseInt(qtyInput.value);
            if (qty > 1) qty--;
            qtyInput.value = qty;

            updateTotal(card);
            updateGrandTotal();
        });
    });

    
    document.querySelectorAll('.prod_selection').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const card = checkbox.closest('.card');

            if (checkbox.checked) {
                updateTotal(card);
            }

            updateGrandTotal();
        });
    });

    updateGrandTotal();
});

// Function to update total price of a single product 
function updateTotal(card) {
    const unitPrice = parseFloat(card.querySelector('.prod_price').value);
    const qty = parseInt(card.querySelector('.order_qty').value);
    const totalEl = card.querySelector('.total_price');

    totalEl.textContent = (unitPrice * qty).toFixed(2);
}

// Function to update grand total from all checked products
function updateGrandTotal() {
    let grandTotal = 0.00;

    document.querySelectorAll('.prod_selection:checked').forEach(checkbox => {
        const card = checkbox.closest('.card');
        const total = parseFloat(card.querySelector('.total_price').textContent);
        grandTotal += total;
    });

    const grandTotalEl = document.getElementById('grandtotal');
    const allTotalSection = document.getElementById('alltotal');

    grandTotalEl.textContent = "₹" + grandTotal.toFixed(2);

    if(grandTotal > 0){
        allTotalSection.style.display = "block";
    }
    else{
        allTotalSection.style.display = "none";
    }

}
</script>
<?php include_once("footer.php"); ?>