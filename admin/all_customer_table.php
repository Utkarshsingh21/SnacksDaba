<?php
include_once("conn.php");
include_once("start.php");


if(isset($_POST['searchbtn'])){
	$customer_name = $_POST['customer_name'];
	$customer_email_mob = $_POST['customer_email_mob'];
	if(!empty($_POST["join_date"])){
		$x = new DateTime($_POST["join_date"]);
		$join_date = $x->format("Y-m-d");
	}
	else{
		$join_date = $_POST["join_date"];
	}
	$sql = "select * from customer_op where cust_name like '%$customer_name%' and (cust_email like '%$customer_email_mob%' or cust_mobno like '%$customer_email_mob%')and ('$join_date' = '' OR cust_signup_dtime >= '$join_date')order by cust_signup_dtime desc";
	$result = mysqli_query($conn,$sql);
}

# if search btn is not clicked
else{
	$sql = "select * from customer_op order by cust_signup_dtime desc";

	$result = mysqli_query($conn,$sql);
}

$i = 1;
?>

<link rel="stylesheet" href="sidebar_style.css">
<main class="main-content px-3 mt-2" id="mainContent">
  <form method="post" class="container border rounded bg-white shadow-sm p-4 mb-2">

    <div class="row mb-2">
      <div class="col-12 col-sm-12 col-md-8 col-lg-8 mb-3">
        <label for="customer_name" class="form-label fs-5 fs-md-4 fs-lg-3">Customer Name</label>
        <input type="search" name="customer_name" id="customer_name" class="form-control fs-6 fs-md-5" placeholder="Customer Name" value="<?php if (isset($_POST['customer_name'])) echo $_POST['customer_name']; ?>">
      </div>
      
      <div class="col-12 col-sm-12 col-md-4 col-lg-4">
        <label for="join_date" class="form-label fs-5 fs-md-4 fs-lg-3">Join Date</label>
        <input type="search" name="join_date" id="join_date" class="form-control fs-6 fs-md-5" placeholder="Choose customer join date" value="<?php if (isset($_POST['join_date'])) echo $_POST['join_date']; ?>">
      </div>
    </div>

    <div class="row mb-2">
    	<div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <label for="customer" class="form-label fs-5 fs-md-4 fs-lg-3">Customer Contact Info</label>
        <input type="search" name="customer_email_mob" id="customer" class="form-control fs-6 fs-md-5" placeholder="Customer mobile or email" value="<?php if (isset($_POST['customer_email_mob'])) echo $_POST['customer_email_mob']; ?>">
      </div>
    </div>

    <div class="col-12 d-flex justify-content-center align-items-end">
      <input type="submit" name="searchbtn" value="Fetch" class="btn btn-primary px-5 fs-5 fs-md-4 mt-3">
    </div>
  </form>

    
  <?php if (mysqli_num_rows($result) == 0) {?><h3 class="text-center fs-4 fw-bold">NO CUSTOMERS WERE FOUND MATCHING GIVEN DETAILS.</h3><?php } 
    else{?>
    	<h1 class="text-dark text-center text-decoration-underline fs-3 fw-bold m-3">CUSTOMER LIST</h1>
    </div>
    <div class="table-responsive m-auto" width="100vh">
    	<table class="table table-bordered table-hover table-warning">
	        <thead>
	            <tr>
	            	<th>Sr.No</th>
	            	<th>Customer Id</th>
	            	<th>Customer Name</th>
	            	<th>Customer Email</th>
	            	<th>Customer Mobile</th>
	            	<th>Customer Address</th>
	            	<th>Customer Pincode</th>
	            	<th>Customer Status</th>
	            	<th>Action</th>
	            </tr>
	        </thead>
	        <tbody>
	            <?php while($row = mysqli_fetch_assoc($result)){
	                $className = ($row["cust_status"] == 'T')?"table-info":"table-danger";
	                ?>
	            	<tr class="<?php echo $className;?>">
	            		<td><?php echo $i++;?></td>
	            		<td><?php echo $row["cust_id"] ;?></td>
	            		<td><?php echo $row["cust_name"] ;?></td>
	            		<td><?php echo $row["cust_email"] ;?></td>
	            		<td><?php echo $row["cust_mobno"] ;?></td>
	            		<td><?php echo $row["cust_address"] ;?></td>
	            		<td><?php echo $row["cust_pincode"] ;?></td>
	            		<td><?php if($row["cust_status"] == 'T'){echo "Active";}else{echo"Deactive";}?></td>
	            		<td><?php if($row["cust_status"] == 'T'){?>
	                         <a href="main.php?flag=21&key=1&cust_id=<?php echo $row['cust_id'] ;?>" style="color:red; font-weight: bold;" title="Disable"><i class="bi bi-x-circle text-danger fs-3 m-4"></i></a><?php
	                        }else{?>
	                            <a href="main.php?flag=21&key=0&cust_id=<?php echo $row['cust_id'] ;?>" style="color:blue;font-weight: bold;" title="Enable"><i class="bi bi-check-circle text-success fs-3 m-4"></i></a>
	                         <?php
	                        }?>
	                    </td>
	            	</tr>
	             <?php
	            }?>
	        </tbody>
        </table>
    </div>
     <?php 
    } ?>
</main>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
	$(function() {
		  $("#join_date").datepicker({
		    dateFormat: "dd-mm-yy",
		    changeMonth: true,
		    changeYear: true,
		    onSelect: function(selectedDate) {
		      var startDate = $(this).datepicker('getDate');
		    }
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

</body>
</html>