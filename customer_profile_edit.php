<?php
include("admin/conn.php");
include_once("first.php"); 
include_once("nav_sidebar.php");



if (!isset($_SESSION["cust_id"])) {
    echo "<p class='text-danger'>You must be logged in to change your password.</p>";
    include("footer.php");
    exit;
}
$cust_id = $_SESSION["cust_id"];
$sql = "select * from customer_op where cust_id = '$cust_id'";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);
?>
    <div class="container-fluid form-container">
        <h3 class="page-title m-4">Edit Your Profile</h3>
        <form name="editprofile" class="w-100 max-w-md px-4" onsubmit="return validate()" method="POST">

            <?php if (isset($_GET["err"]) && $_GET["err"] == 1){ ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo "Enter different email or mobile number"; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
             <?php 
            } ?>

            <?php if (isset($_GET["err"]) && $_GET["err"] == 2){ ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo "ERROR:Change Was Not Sucessfull"; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
             <?php 
            } ?>
            
            <input type="hidden" name="cust_id" value="<?php echo $cust_id; ?>">
            <div class="mb-3">
                <label class="form-label fw-bold">Name</label>
                <input type="text" name="cust_name" class="form-control w-100" value="<?php echo $row["cust_name"]; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input type="text" name="cust_email" class="form-control w-100" value="<?php echo $row["cust_email"]; ?>">
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Mobile Number</label>
                <input type="text" name="cust_mobile"  class="form-control w-100" onkeyup="validateMobile()" maxlength="10" value="<?php echo $row["cust_mobno"]; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Address</label>
                <textarea row="2" col="40" name="cust_address" class="form-control w-100" value=""><?php echo $row["cust_address"];?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Pincode</label>
                <input type="text" name="cust_pincode" class="form-control w-100" value="<?php echo $row["cust_pincode"]; ?>">
            </div>
           

            <div class="text-center my-3">
                <input type="submit" value="Change" class="btn btn-primary btn-lg w-100 fw-bold">
            </div>
        </form>
    </div>
    <script>
        function validateMobile() {
            const input = editprofile.cust_mobile.value;
            const value = input.trim();
        
            for (let i = 0; i < value.length; i++) {
                const char = value[i];
                if (char < '0' || char > '9') {
                    alert("Only numeric digits are allowed");
                    editprofile.cust_mobile.focus();
                    return editprofile.cust_mobile.value = "";
                }
            }
        }

        function validate(){
            const name = editprofile.cust_name.value;
            const email = editprofile.cust_email.value;
            const mobile = editprofile.cust_mobile.value;
            const address  = editprofile.cust_address.value;

            const atIndex = email.indexOf('@');
            const dotIndex = email.lastIndexOf('.');

            if(name == ""){
                alert("Enter Your Name");
                editprofile.cust_name.focus();
                return false
            }

            if(email == ""){
                alert("Enter email");
                editprofile.cust_email.focus();
                return false;
            }

            if (atIndex < 1 || dotIndex <= atIndex + 1 || dotIndex === email.length - 1) {
                alert("Enter valid email");
                editprofile.cust_email.focus();
                return false;
            }

            if(mobile == ""){
                alert("Enter 10 digit mobile Number");
                editprofile.cust_mobile.focus();
                return false;
            }

            if (mobile.length > 0 && mobile.length < 10) {
                alert("Mobile number must be 10 digits.");
                editprofile.cust_mobile.focus();
                return false;
            }

            if(address == ""){
                alert("Enter Your address");
                editprofile.cust_address.focus();
                return false;
            }

            
            editprofile.action="admin/main.php?flag=18";
        }
    </script>
<?php include_once("footer.php"); ?>