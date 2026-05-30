<?php
include("admin/conn.php");
include_once("first.php"); 
include_once("nav_sidebar.php");

if (!isset($_SESSION["cust_id"])) {
    echo "<p class='text-danger text-center mt-5'>You must be logged in to change your password.</p>";
    include("footer.php");
    exit;
}
?>
<div class="container-fluid vh-100 d-flex justify-content-center align-items-center">

    <form name="changepasswordform" 
          class="password-card"
          onsubmit="return validate()"  
          method="POST">

        <!-- ERROR ALERT -->
        <?php if (isset($_GET["err"])) { ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i>
                Enter Valid Current Password
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <!-- TITLE -->
        <div class="text-center mb-4">
            <i class="bi bi-shield-lock fs-2 text-primary"></i>
            <h4 class="card-title mt-2">Change Password</h4>
        </div>

        <input type="hidden" name="cust_id" value="<?= $_SESSION["cust_id"]; ?>">

        <!-- CURRENT PASSWORD -->
        <div class="mb-3">
            <label class="form-label">Current Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="curr_pass" class="form-control" required>
            </div>
        </div>

        <!-- NEW PASSWORD -->
        <div class="mb-3">
            <label class="form-label">New Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-key"></i></span>
                <input type="password" name="new_pass" class="form-control" required>
            </div>
        </div>

        <!-- CONFIRM PASSWORD -->
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-check2-circle"></i></span>
                <input type="password" name="cnf_new_pass" class="form-control" required>
            </div>
        </div>

        <!-- BUTTON -->
        <div class="d-grid mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-arrow-repeat"></i> Update Password
            </button>
        </div>

    </form>

</div>

<script>
function validate(){
    const curr_pass = changepasswordform.curr_pass.value;
    const new_pass  = changepasswordform.new_pass.value;
    const cnf       = changepasswordform.cnf_new_pass.value;

    if(curr_pass == ""){
        alert("Enter Current Password");
        return false;
    }

    if(new_pass == ""){
        alert("Enter New Password");
        return false;
    }

    if(cnf == ""){
        alert("Enter Confirm New Password");
        return false;
    }

    if(new_pass != cnf){
        alert("Passwords do not match");
        return false;
    }

    changepasswordform.action="admin/main.php?flag=17";
}
</script>

<?php include_once("footer.php"); ?>