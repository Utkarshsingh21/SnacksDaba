<?php
include("admin/conn.php");
include_once("first.php"); 
include_once("nav_sidebar.php");
?>
<div class="container-fluid">

<!-- ALERTS -->
<?php if(isset($_GET["msg"]) && $_GET["msg"] == 1){ ?>
<div class="alert alert-success alert-dismissible fade show mt-3">
    Address saved successfully
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php } ?>

<?php if(isset($_GET["err"]) && $_GET["err"] == 1){ ?>
<div class="alert alert-danger alert-dismissible fade show mt-3">
    Error saving address
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php } ?>

<!-- FORM -->
<div class="address-wrapper">

<h4 class="page-title mb-4">
    <i class="bi bi-geo-alt"></i> Add Delivery Address
</h4>

<form name="addressform" onsubmit="return validate()" method="POST">

<!-- SECTION 1 -->
<div class="section-title">Basic Details</div>

<div class="row row-gap">
    <div class="col-md-6">
        <label class="form-label">Full Name</label>
        <input type="text" name="reciever_name" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">Mobile Number</label>
        <input type="text" name="reciever_mob" class="form-control" onkeyup="validateMobile()">
    </div>
</div>

<!-- SECTION 2 -->
<div class="section-title mt-4">Address Details</div>

<div class="mb-3">
    <label class="form-label">Full Address</label>
    <textarea name="reciever_address" rows="3" class="form-control"></textarea>
</div>

<div class="row row-gap">
    <div class="col-md-4">
        <label class="form-label">Pincode</label>
        <input type="text" name="reciever_pincode" class="form-control">
    </div>

    <div class="col-md-4">
        <label class="form-label">Address Type</label>
        <select name="address_type" class="form-select">
            <option value="">Select</option>
            <option value="home">Home</option>
            <option value="office">Office</option>
        </select>
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="myCheckbox" name="checkbox">
            <label class="form-check-label">Default</label>
        </div>
    </div>
</div>

<input type="hidden" name="cust_id" value="<?php echo $_SESSION['cust_id']; ?>">

<!-- BUTTON -->
<div class="mt-4 text-end">
    <button type="submit" class="btn btn-primary px-4">
        <i class="bi bi-save"></i> Save Address
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
    const checkbox = document.querySelector('#myCheckbox');

    if(type == "") return alert("Select address type"), false;
    if(rname == "") return alert("Enter name"), false;
    if(rmob.length != 10) return alert("Enter valid mobile"), false;
    if(raddress == "") return alert("Enter address"), false;
    if(rpin == "") return alert("Enter pincode"), false;

    checkbox.value = checkbox.checked ? "T" : "F";

    addressform.action = "admin/main.php?flag=26";
}
</script>
<?php include_once("footer.php"); ?>