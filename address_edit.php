<?php
include("admin/conn.php");
include_once("first.php"); 
include_once("nav_sidebar.php");

if(empty($_SESSION["cust_name"])){
  header("location:customer_login.php");
}

if(isset($_GET['add_id'])){
  $add_id = $_GET['add_id'];
}

$cust_id = $_SESSION['cust_id'];

$sql = "select * from address_op 
        where cust_id = '$cust_id' 
        and address_id = '$add_id'";

$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);

$name = $row["name"];
$mobile = $row["mobile"];
$address = $row["address"];
$pincode = $row["pincode"];
$type = $row["address_type"];
?>

<div class="container-fluid">

<!-- ALERTS -->
<?php if(isset($_GET["msg"]) && $_GET["msg"] == 1){ ?>
<div class="alert alert-success alert-dismissible fade show mt-3">
    Address updated successfully
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php } ?>

<?php if(isset($_GET["err"]) && $_GET["err"] == 1){ ?>
<div class="alert alert-danger alert-dismissible fade show mt-3">
    Error updating address
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php } ?>

<!-- FORM -->
<div class="address-wrapper">

<h4 class="page-title mb-4">Edit Address</h4>

<form name="addressform" onsubmit="return validate()" method="POST">

<!-- BASIC DETAILS -->
<div class="section-title">Basic Details</div>

<div class="row row-gap">
    <div class="col-md-6">
        <label class="form-label">Full Name</label>
        <input type="text" name="reciever_name" class="form-control"
               value="<?= $name ?>">
    </div>

    <div class="col-md-6">
        <label class="form-label">Mobile Number</label>
        <input type="text" name="reciever_mob" class="form-control"
               value="<?= $mobile ?>" onkeyup="validateMobile()">
    </div>
</div>

<!-- ADDRESS DETAILS -->
<div class="section-title mt-4">Address Details</div>

<div class="mb-3">
    <label class="form-label">Full Address</label>
    <textarea name="reciever_address" rows="3" class="form-control"><?= $address ?></textarea>
</div>

<div class="row row-gap">
    <div class="col-md-4">
        <label class="form-label">Pincode</label>
        <input type="text" name="reciever_pincode" class="form-control"
               value="<?= $pincode ?>">
    </div>

    <div class="col-md-4">
        <label class="form-label">Address Type</label>
        <select name="address_type" class="form-select">
            <option value="home" <?= ($type == "home") ? "selected" : "" ?>>Home</option>
            <option value="office" <?= ($type == "office") ? "selected" : "" ?>>Office</option>
        </select>
    </div>
</div>

<input type="hidden" name="cust_id" value="<?= $_SESSION['cust_id']; ?>">
<input type="hidden" name="add_id" value="<?= $add_id; ?>">

<!-- BUTTON -->
<div class="mt-4 text-end">
    <button type="submit" class="btn btn-primary">
        Update Address
    </button>
</div>

</form>
</div>

</div>

<script>
function validateMobile() {
    const val = addressform.reciever_mob.value;
    if (!/^[0-9]*$/.test(val)) {
        alert("Only digits allowed");
        addressform.reciever_mob.value = "";
    }
}

function validate(){
    const type  = addressform.address_type.value;
    const rname = addressform.reciever_name.value;
    const rmob  = addressform.reciever_mob.value;
    const raddress = addressform.reciever_address.value;
    const rpin = addressform.reciever_pincode.value;

    if(type == "") return alert("Select address type"), false;
    if(rname == "") return alert("Enter name"), false;
    if(rmob.length != 10) return alert("Enter valid mobile"), false;
    if(raddress == "") return alert("Enter address"), false;
    if(rpin == "") return alert("Enter pincode"), false;

    addressform.action = "admin/main.php?flag=28";
}
</script>
<?php include_once("footer.php"); ?>