<?php
include_once("admin/conn.php");
include_once("first.php"); 
include_once("nav_sidebar.php"); 

// 1. Check login
if (empty($_SESSION["cust_name"])) {
    if (isset($_POST['p_id'])) {
        $_SESSION['p_id'] = $_POST['p_id'];
    }
    header("Location: customer_login.php");
    exit;
}

$p_id = $_SESSION['orderd_prod'];
$cust_id = $_SESSION["cust_id"];
// $add_id = $_SESSION["orderd_add_id"];
// $order_price = $_SESSION["orderd_prod_price"];
// $total_price = $_SESSION["orderd_total"];
// $order_qty = $_SESSION["orderd_qty"];
// $payment_id = $_SESSION['razorpay_payment_id'];
// $payment_dtime = $_SESSION['razorpay_payment_dtime'];

// date_default_timezone_set('Asia/Kolkata');
// $order_dtime = date('Y-m-d h:i:s');

// $sql = "insert into main_order_table(cust_id,order_dtime,order_amount,delivery_address,payment_id,payment_dtime) values('$cust_id','$order_dtime','$total_price','$add_id','$payment_id','$payment_dtime')";
// mysqli_query($conn, $sql);



// $sqlx = "select order_id from main_order_table where cust_id = '$cust_id' order by order_dtime desc limit 1";
// $resultx = mysqli_query($conn, $sqlx);
// $row    = mysqli_fetch_assoc($resultx);
// $order_id = $row["order_id"];

// $sqly = "insert into suborder_op(prod_id,order_qty,order_dtime,order_price,order_tamount,order_id) values('$p_id','$order_qty','$order_dtime','$order_price','$total_price','$order_id')";
// $resulty = mysqli_query($conn, $sqly);





$sql    = "select pc_id from product_detail where p_id = '$p_id'";
$result = mysqli_query($conn,$sql);
$var    = mysqli_fetch_assoc($result);
$pc_id  = $var["pc_id"];

$products = "select * from product_detail where pc_id = '$pc_id' and p_id != '$p_id'";
$products_result = mysqli_query($conn,$products);

?>

<div class="container-fluid mt-4" id="main-content">
    <div class="d-flex justify-content-center">
        <div class="border border-dark rounded p-4 shadow-sm text-center" style="max-width: 500px; width: 100%;">
            
            
            <i class="bi bi-check-circle-fill text-danger" style="font-size: 6rem;"></i>

            <!-- Headings -->
            <h2 class="text-success pt-3 pb-2">Payment Was Failed</h2>
            <h5>Order Was Not Placed. Try Again</h5>
            
        </div>
    </div> 

    <div class="row mt-4">
        <div class="col">
            <h3>You Might Also like this:</h3>
        </div>
    </div>

    <div class="row">
        <?php if(mysqli_num_rows($products_result) > 0){?>
            <?php while($row = mysqli_fetch_assoc($products_result)){?>
                <div class="col-12 col-sm-6 col-md-3 col-lg-3 d-flex justify-content-center pt-2 pb-4">
                    <div class="card h-100" style="width:100%;">
                        <img src="<?php echo $row["product_image"]; ?>" class="card-img-top" style="object-fit:cover; height:50%;">

                        <div class="card-body">
                            <h5 class="card-title text-primary responsive-font"><?php echo $row["product_name"]; ?></h5>
                            <div class="d-flex flex-wrap flex-md-nowrap justify-content-between">
                                <p class="responsive-font mb-0 p-0"><i class="bi bi-currency-rupee responsive-font"></i><?php echo $row["product_price"]; ?></p>
                                <p class="responsive-font mb-0 p-0"><?php echo $row["product_weight_qty"] . $row["product_weight_unit"]; ?></p>
                            </div>

                            <div class="d-flex flex-nowrap justify-content-center pt-4">
                                <form method="POST" action="ordernow.php" class="flex-fill responsive-font">
                                  <input type="hidden" name="p_id" value="<?= $row['p_id'] ?>">
                                  <button type="submit" class="btn btn-warning btn-sm responsive-font w-100">Order Now</button>
                                </form>
                            </div> 

                            <div class="d-flex flex-nowrap justify-content-center pt-4">
                                <form method="POST" action="admin/main.php?flag=23" class="flex-fill responsive-font">
                                    <input type="hidden" name="p_id" value="<?= $row['p_id'] ?>">
                                    <button type="submit" class="btn btn-primary btn-sm responsive-font  w-100" name="cart_btn">ADD TO CART</button>
                                </form>
                            </div>        

                        </div>    
                    </div>    
                </div>
            <?php 
        } ?> 
        <?php 
        }?>   

    </div>         

</div> 

</body>
</html>    