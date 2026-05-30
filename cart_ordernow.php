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

$total = 0;
$products = [];
if (isset($_POST['prod_selection'])) {
    $_SESSION["orderd_prod"] = $_POST['prod_selection'];
    $_SESSION["cart_prod_qty"] = $_POST['order_qty'];
    $_SESSION["cart_prod_price"] = $_POST['prod_price'];
    $_SESSION["cart_dev_add"] = $_POST["delivery_address"];

}



foreach ($_POST['prod_selection'] as $productId) {
    $qty = $_POST['order_qty'][$productId] ?? 0;
    $price = $_POST['prod_price'][$productId] ?? 0;
    $subtotal = $qty * $price;
    $total += $subtotal;

    $sql = "SELECT p_id, product_name, product_price, product_image, product_weight_qty, product_weight_unit  FROM product_detail WHERE p_id = '$productId'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 0) {
        die("Product not found.");
    }

    $product = mysqli_fetch_assoc($result);
    $product['qty'] = $qty;
    $product['price'] = $price;
    $product['subtotal'] = $subtotal;

    $products[] = $product;  // Add to array
}

// Fetch delivery address
$cust_id = $_SESSION["cust_id"];
$add_id = $_POST["delivery_address"]; // or wherever you're getting it from
$address_query = "SELECT * FROM address_op WHERE cust_id = '$cust_id' AND address_id = '$add_id'";
$address_result = mysqli_query($conn, $address_query);
$address = mysqli_fetch_assoc($address_result);
?>

<div class="container-fluid" id="main-content">
    <div class="row align-items-center">
        <div class="col-12">
            <div class="card border-2 border-dark shadow-sm m-4 p-4">
                <?php foreach ($products as $product): ?>
                    <div class="row align-items-center my-2">
                        <div class="col-auto">
                            <img src="<?= htmlspecialchars($product['product_image']) ?>" alt="Product" class="image-fluid rounded" style="max-width: 100%; height: 25vh; object-fit: cover;">
                        </div>

                        <div class="col d-flex justify-content-end align-items-center">
                            <h3 class="mb-0 pe-2">Quantity:</h3>
                            <h3 class="text-primary mb-0"><?= htmlspecialchars($product['qty']) ?></h3>
                        </div>
                    </div>
                    <hr class="border-top border-2">

                    <div class="row mb-2"> 
                        <div class="col"><h3>Product:</h3></div>
                        <div class="col text-end"><h5><?= htmlspecialchars($product['product_name']); ?></h5></div>
                    </div>

                    <div class="row mb-2"> 
                        <div class="col"><h3>Unit Price:</h3></div>
                        <div class="col text-end">
                            <h5><span>₹<?= number_format($product['price'], 2); ?></span> per <span> <?= htmlspecialchars($product['product_weight_qty'] . $product['product_weight_unit']) ?></span></h5>
                        </div>
                    </div>

                    <div class="row mb-2"> 
                        <div class="col"><h3>Total Price:</h3></div>
                        <div class="col text-end"><h5>₹<?= number_format($product['subtotal'], 2); ?></h5></div>
                    </div>
                <?php endforeach; ?>

                <div class="row mb-2"> 
                    <div class="col"><h3>Receiver's Name:</h3></div>
                    <div class="col text-end"><h5><?= htmlspecialchars($address["name"]); ?></h5></div>
                </div>

                <div class="row mb-2"> 
                    <div class="col"><h3>Delivery Address:</h3></div>
                    <div class="col text-end">
                        <h5><?= htmlspecialchars($address["address"] . " " . $address["pincode"] . " (" . $address["address_type"] . ")"); ?></h5>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col"><h3>Grand Total:</h3></div>
                    <div class="col text-end"><h5>₹<?= number_format($total, 2); ?></h5></div>
                </div>

                <div class="row mb-2">
                    <div class="col-12"> 
                        <button id="rzp-button1" type="button" class="btn btn-warning w-100">Pay Now</button>
                    </div>    
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
  const options = {
    "key": "rzp_test_RGJ1hfRVhqu7MS", 
    "amount": <?= $total * 100 ?>, // Amount in paise
    "currency": "INR",
    "name": "SnacksDhaba",
    "description": "Order Payment",
    "image": "https://yourdomain.com/logo.png", // Optional
    "handler": function (response) {
      // Send payment details to your server
      fetch('payment_verification2.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          razorpay_payment_id: response.razorpay_payment_id,
          cust_id: <?= json_encode($cust_id) ?>,
          delivery_address: <?= json_encode($add_id) ?>,
          total_amount: <?= json_encode($total) ?>,
          products: <?= json_encode($products) ?>
        })
      })
      .then(res => res.text())
      .then(data => {
        alert("Payment Successful!");
        window.location.href = "order_success2.php";
      })
    },
    "prefill": {
      "name": <?= json_encode($_SESSION["cust_name"]) ?>,
      "email": "customer@example.com",  
      "contact": "9000090000"           
    },
    "theme": {
      "color": "#3399cc"
    }
  };

  const rzp1 = new Razorpay(options);
  document.getElementById('rzp-button1').onclick = function(e) {
    rzp1.open();
    e.preventDefault();
  };
</script>

<?php include_once("footer.php"); ?>