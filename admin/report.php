<?php 
include_once("conn.php");
include_once("start.php");

#categoy
$sql = "select pc_id,product_category_name from product_category";
$result = mysqli_query($conn,$sql);

#product
$sql2 = "select p_id,product_name from product_detail";
$result2 = mysqli_query($conn,$sql2);

#search query
if(isset($_POST['searchbtn'])){
	$searchPerformed = true;
	$category = $_POST['category'];
	$product  = $_POST["product"];

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

	$order_status = $_POST["order_status"];
	$customer     = $_POST["customer"];

	$sqlx ="select t4.product_category_name,t3.product_name,t3.product_price,t2.cust_name,t2.cust_email,t2.cust_mobno,t1.order_id,t1.order_status,t1.order_qty,t1.order_tamount,t1.order_dtime,t1.remark,t5.remark as main_remark from suborder_op t1 inner join main_order_table t5 on t1.order_id = t5.order_id  inner join customer_op t2 on t5.cust_id = t2.cust_id inner join product_detail t3 on t1.prod_id = t3.p_id inner join product_category t4 on t3.pc_id = t4.pc_id where t4.pc_id LIKE '%$category%' and  t3.product_name like '%$product%' and (('$from_date' = '' OR t1.order_dtime >= '$from_date')
  AND
    ('$to_date' = '' OR t1.order_dtime < DATE_ADD('$to_date', INTERVAL 1 DAY))) and t1.order_status like '%$order_status%' and (t2.cust_email like '%$customer%' or t2.cust_mobno like '%$customer%')";

  $resultx = mysqli_query($conn,$sqlx);
}
$i = 1;
?>


<link rel="stylesheet" href="sidebar_style.css">
<main class="main-content px-3 mt-2" id="mainContent">
  <form method="post" class="container border rounded bg-light shadow-sm p-4">
      
    <div class="row mb-3">
      <div class="col-12 col-sm-12 col-md-5 col-lg-4 mb-3">
        <label for="product" class="form-label fw-semibold">Product</label>
        <input type="search" name="product" id="product" class="form-control" placeholder="Search products" value="<?php if(isset($_POST['product'])) echo $_POST['product']; ?>">
      </div>
        

      <div class="col-12 col-sm-12 col-md-3 col-lg-4 mb-3">
        <label for="order_status" class="form-label fw-semibold">Order Status</label>
        <select name="order_status" id="order_status" class="form-select">
          <option value="">ALL</option>
          <option value="P" <?php if(isset($_POST["order_status"]) && $_POST["order_status"] == "P") echo "selected"; ?>>PLACED</option>
          <option value="D" <?php if(isset($_POST["order_status"]) && $_POST["order_status"] == "D") echo "selected"; ?>>DELIVERED</option>
          <option value="C" <?php if(isset($_POST["order_status"]) && $_POST["order_status"] == "C") echo "selected"; ?>>CANCELED</option>
        </select>
      </div>

      <div class="col-12 col-sm-12 col-md-4 col-lg-4">
        <label for="category" class="form-label fw-semibold">Product Category</label>
        <select name="category" id="category" class="form-select">
          <option value="">ALL CATEGORY</option>
            <?php while($row = mysqli_fetch_assoc($result)){ ?>
              <option value="<?php echo $row["pc_id"]; ?>" <?php if (isset($_POST["category"]) && $_POST["category"] == $row["pc_id"]) echo "selected"; ?>><?php echo $row["product_category_name"]; ?></option>
            <?php } ?>
        </select>
      </div>
      </div>

    </div>

    <div class="row mb-3">
      <div class="col-12 col-sm-12 col-md-4 col-lg-4 mb-3">
        <label for="customer" class="form-label fw-semibold">Customer Detail</label>
        <input type="search" name="customer" id="customer" class="form-control" placeholder="Mobile/E-mail" value="<?php if(isset($_POST['customer'])) echo $_POST['customer']; ?>">
      </div>

      <div class="col-12 col-sm-12 col-md-4 col-lg-4 mb-3">
        <label for="start_date" class="form-label fw-semibold">Order Start Date</label>
        <input type="text" name="from_date" id="start_date" class="form-control" value="<?php if(isset($_POST['from_date'])) echo $_POST['from_date']; ?>">
      </div>
        

      <div class="col-12 col-sm-12 col-md-4 col-lg-4 mb-3">
        <label for="end_date" class="form-label fw-semibold">Order End Date</label>
        <input type="text" name="to_date" id="end_date" class="form-control" value="<?php if(isset($_POST['to_date'])) echo $_POST['to_date']; ?>">
      </div>
    </div>

    <div class="row">
      <div class="col-12 col-md-12 d-flex justify-content-center">
        <input type="submit" name="searchbtn" value="Fetch" class="btn btn-primary fs-4" style="width:70%">
      </div>
    </div>
  </form>



  
	<?php if(isset($_POST['searchbtn']) && $searchPerformed == true){?>
		<?php if(mysqli_num_rows($resultx) == 0){?> <h2 class="d-flex justify-content-center text-dark m-auto fs-4 fw-bold">NO ORDERS WERE FOUND WITH GIVEN DETAILS</h2><hr><?php }
		else{?>
			<div class="table-responsive m-4">
        <table class="table table-bordered table-hover">
	        <thead class="table-dark">
            <tr>
              <th>Sr.No</th>
              <th>Order Id</th>
              <th>Customer Info</th>
              <th>Product Info</th>
              <th>Ordered Quantity</th>
              <th>Toatal Amount</th>
              <th>Order Status</th>
            </tr>
	        </thead>
	        <tbody>
	        	<?php while($row = mysqli_fetch_assoc($resultx)){
	        		$className = ($row["order_status"] == 'P') ? "table-success" : 
                 (($row["order_status"] == 'C') ? "table-danger" : 
                 (($row["order_status"] == 'S') ? "table-info" : 
                 (($row["order_status"] == 'D') ? "table-success" : "")));?>
	        		<tr class="<?php echo $className;?>">
	        			<td><?php echo $i++;?></td>
	        			<td><?php echo $row["order_id"] ;?></td>
                <td class="w-25"><i class="bi bi-person fs-5"></i>-><?php echo $row["cust_name"] ;?><br><i class="bi bi-envelope fs-5"></i>-><?php echo $row["cust_email"] ;?><br><i class="bi bi-telephone fs-5"></i>-><?php echo $row["cust_mobno"] ;?></td>

                <td class="w-25">Category:<?php echo $row["product_category_name"] ;?><br><?php echo "Name->".$row["product_name"] ;?></td>
                <td class="w-20"><?php echo $row["order_qty"] ;?><br>Unit Price:<br><i class="bi bi-currency-rupee"></i><?php echo $row["product_price"] ;?></td>

                <td><i class="bi bi-currency-rupee"></i><?php echo $row["order_tamount"] ;?></td>
                <td><?php if($row["order_status"] == 'P'){echo "Placed";}elseif($row["order_status"] == 'D'){echo "Delievered";}elseif($row["order_status"] == 'S'){echo "Shipped";}else{echo"Canceled";}?>
                  <?php if(isset($row["remark"])){echo "<br>"."REMARK:".$row["remark"];}?>
                  <?php if(isset($row["main_remark"])){echo "<br>"."REMARK:".$row["main_remark"];}?>
                </td>
              </tr>
            <?php }?>          
          </tbody>
        </table>
      </div>
    <?php }?>
	<?php }?>
</main>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  $(function() {
    $("#start_date").datepicker({
      dateFormat: "dd-mm-yy",
      changeMonth: true,
      changeYear: true,
      onSelect: function(selectedDate) {
        var startDate = $(this).datepicker('getDate');
        $("#end_date").datepicker("option", "minDate", startDate);
      }
    });

    $("#end_date").datepicker({
      dateFormat: "dd-mm-yy",
      changeMonth: true,
      changeYear: true
    });
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