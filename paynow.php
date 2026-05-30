<?php
include_once("admin/conn.php");
include_once("first.php"); 
include_once("nav_sidebar.php"); 
include_once("bot.html");

if (empty($_SESSION["cust_name"])) {
    if (isset($_POST['p_id'])) {
        $_SESSION['p_id'] = $_POST['p_id'];
    }
    header("Location: customer_login.php");
    exit;
}

$prod_id = $_POST["prod_id"];
$cust_id = $_POST["cust_id"];
$add_id = addslashes($_POST["delivery_address"]);
$order_qty = $_POST["order_qty"];
$order_price = $_POST["prod_price"];
$total_price = $order_qty * $order_price;

$_SESSION["orderd_prod"] = $prod_id;
$_SESSION["orderd_add_id"] = $add_id;
$_SESSION["orderd_prod_price"] = $order_price;
$_SESSION["orderd_total"] = $total_price;
$_SESSION["orderd_qty"]  = $order_qty;


$sql = "SELECT p_id, product_name, product_price, product_image FROM product_detail WHERE p_id = '$prod_id'";
$result = mysqli_query($conn,$sql);

if (mysqli_num_rows($result) === 0) {
    die("Product not found.");
}
else{
$product = mysqli_fetch_assoc($result);
}

$address_query = "select * from address_op where cust_id = '$cust_id' and address_id = '$add_id'";
$address_result       = mysqli_query($conn,$address_query);
$address = mysqli_fetch_assoc($address_result);
include_once("nav_sidebar.php");
?>

<div class="container-fluid" id="main-content">
    <div class="row align-items-center">
        <div class="col-12 d-flex justify-content-center">
            <div class="card border-2 border-dark shadow-sm h-100 w-100 ps-2 pe-2 mt-2">


                <div class="row align-items-center my-2">
                    <div class="col-auto">
                        <img src="<?= htmlspecialchars($product['product_image']) ?>" alt="Product" class="image-fluid rounded" style="max-width: 100%; height: 25vh; object-fit: cover;">
                    </div>

                    <div class="col d-flex justify-content-end align-items-center">
                        <h3 class="mb-0 pe-2">Quantity:</h3>
                        <h3 class="text-primary mb-0"><?= htmlspecialchars($order_qty) ?></h3>
                    </div>
                </div> 
                
                <hr class="border-top border-danger border-3">
                
                <div class="row mb-2"> 
                    <div class="col"><h3>Product:</h3></div>
                    <div class="col text-end"><h5><?php echo $product['product_name']; ?></h5></div>
                </div>  
                
                <div class="row mb-2"> 
                    <div class="col"><h3>Unit Price:</h3></div>
                    <div class="col text-end"><h5>₹<?php echo $order_price; ?></h5></div>
                </div>  

                <div class="row mb-2"> 
                    <div class="col"><h3>Total Price:</h3></div>
                    <div class="col text-end"><h5>₹<?php echo number_format($total_price, 2); ?></h5></div>
                </div>
                
                <div class="row mb-2"> 
                    <div class="col"><h3>Reciever's Name:</h3></div>
                    <div class="col text-end"><h5><?php echo $address["name"]; ?></h5></div>
                </div>

                <div class="row mb-2"> 
                    <div class="col"><h3>Delivery Address:</h3></div>
                    <div class="col text-end"><h5><?php echo $address["address"]." ".$address["pincode"]." "."(".$address["address_type"].")"; ?></h5></div>
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
  "amount": <?= $total_price * 100 ?>,
  "currency": "INR",
  "name": "SnacksDhaba",
  "description": "Order Payment",

  "handler": function (response) {
    fetch('payment_verification.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        razorpay_payment_id: response.razorpay_payment_id,
        cust_id: <?= json_encode($cust_id) ?>,
        delivery_address: <?= json_encode($add_id) ?>,
        total_amount: <?= json_encode($total_price) ?>,
        product_id: <?= json_encode($prod_id) ?>,
        quantity: <?= json_encode($order_qty) ?>
      })
    })
    .then(res => res.text())
    .then(data => {
      alert("Payment Successful!");
      window.location.href = "order_success.php";
    });
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
</body>
</html>