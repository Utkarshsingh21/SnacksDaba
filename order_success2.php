<?php
include("admin/conn.php");
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


$cust_id = $_SESSION['cust_id'];
date_default_timezone_set('Asia/Kolkata');
$dtime = date('Y-m-d h:i:s');
$total = 0;
$add_id = $_SESSION["cart_dev_add"];
$payment_id = $_SESSION['razorpay_payment_id'];
$payment_dtime = $_SESSION['razorpay_payment_dtime'];
if (!empty($_SESSION['orderd_prod'])) {
    $sql = "insert into main_order_table(cust_id,order_dtime,delivery_address,payment_id,payment_dtime) values('$cust_id','$dtime','$add_id','$payment_id','$payment_dtime')";
    mysqli_query($conn, $sql);

    $sql = "select order_id from main_order_table where cust_id = '$cust_id' order by order_dtime desc limit 1";
    $result = mysqli_query($conn, $sql);
    $row    = mysqli_fetch_assoc($result);
    $order_id = $row["order_id"];
    foreach ($_SESSION['orderd_prod'] as $productId) {
        $qty = $_SESSION["cart_prod_qty"][$productId] ?? 0;
        $price = $_SESSION["cart_prod_price"][$productId] ?? 0;
        $subtotal = $qty * $price;
        $total += $subtotal;

        $sql = "insert into suborder_op(prod_id,order_qty,order_price,order_tamount,order_dtime,order_id) values('$productId','$qty','$price','$subtotal','$dtime','$order_id')";
        mysqli_query($conn, $sql);

        $sql2 = "update cart set cart_flag = 'T' where p_id = '$productId' and cust_id = '$cust_id'";
        mysqli_query($conn, $sql2);
    }
    $sql = "update main_order_table set order_amount = '$total' where order_id = '$order_id'";
    mysqli_query($conn, $sql);
}    


if(is_array($_SESSION['orderd_prod'])) {
    $p_id = ($_SESSION['orderd_prod']);

    $_SESSION['orderd_prod'] = "";

    $pc_id = [];

    foreach ($p_id as $id) {
        
        $sql = "SELECT pc_id FROM product_detail WHERE p_id = $id";
        $result = mysqli_query($conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            $pc_id[] = $row['pc_id']; // append to array
        }
    }

    $p_id = implode(",",$p_id);
    $pc_id = implode(",",$pc_id);
    // echo "p_id".$p_id;
    // echo "pc_id".$pc_id;

    $products = "select * from product_detail where pc_id IN ($pc_id) and p_id NOT IN ($p_id)";
    $products_result = mysqli_query($conn,$products);
}
include_once("nav_sidebar.php");
?>
<div class="container-fluid mt-4" id="main-content">
    <div class="d-flex justify-content-center">
        <div class="border border-dark rounded p-4 shadow-sm text-center" style="max-width: 500px; width: 100%;">
            
            
            <i class="bi bi-check-circle-fill text-success" style="font-size: 6rem;"></i>

            <!-- Headings -->
            <h2 class="text-success pt-3 pb-2">Payment Was Successful</h2>
            <h5>Order Was Successfully Placed</h5>
            
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