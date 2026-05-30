<?php
include("admin/conn.php");
include_once("first.php"); 
include_once("nav_sidebar.php");

if(empty($_SESSION["cust_name"])){
  header("location:customer_login.php");
}
$cust_id = $_SESSION['cust_id'];

$sql = "select * from address_op where cust_id = '$cust_id' order by address_dtime desc";
$result = mysqli_query($conn,$sql);
$i = 1;
?>
<div class="container mt-4" id="main-content">
	<div class="d-flex justify-content-between mb-4 flex-wrap gap-2">
	  <h2 class="text-center text-md-start">Saved Addresses</h2>
	  <a href="address.php" class="btn btn-outline-primary h-100">Add Address</a>
  </div>
  <div class="table-responsive mt-4">
    <table class="table table-bordered table-striped table-hover">
      <thead class="table-dark">
      	<?php if(mysqli_num_rows($result) == 0){echo '<h2 class="text-center">NO ADDRESS FOUND<h2><hr>';}else{?>
	        <tr>
	          <th>Sr.No</th>
	          <th>Name</th>
	          <th>Phone</th>
	          <th>Address</th>
	          <th>Pincode</th>
	          <th>Address Type</th>
	          <th>Action</th>
	        </tr>
	      </thead>
	      <tbody>
	      	<?php while($row = mysqli_fetch_assoc($result)){?>
		        <tr>
		        	<td><?php echo $i++; ?></td>
			        <td><?php echo $row["name"];?></td>
			        <td><?php echo $row["mobile"];?></td>
			        <td><?php echo $row["address"];?></td>
			        <td><?php echo $row["pincode"];?></td>
			        <td><?php echo $row["address_type"];?></td>
			        <td><?php if($row["is_default"] != 'T'){?> <a href="admin/main.php?flag=29&add_id=<?php echo $row["address_id"]; ?>" title="set default"><i class="bi bi-patch-check-fill"></i></a>||<?php } ?><?php if($row["is_default"] == 'T'){?> <i class="bi bi-check-circle-fill"></i>||  <?php } ?><a href="address_edit.php?add_id=<?php echo $row["address_id"]; ?>" target="_blank" title="Edit"><i class="bi bi-pencil"></i></a>||<a href="admin/main.php?flag=27&add_id=<?php echo $row["address_id"]; ?>"  title ="Delete" onclick="return confirm('Are you sure??')"><i class="bi bi-trash"></i></a>
			        	
			        </td>
		        </tr>
            <?php } ?>
	      </tbody>
	    <?php } ?>
    </table>
  </div>
</div>
<?php include_once("footer.php"); ?>