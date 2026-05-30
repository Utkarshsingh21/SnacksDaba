<?php
include_once("start.php");
if(isset($_GET["err"])){
    $err = $_GET["err"];
    if($err == 1){
        $err = '<h4 class="text-danger">Enter Valid Current Password.</h4>';
    }
}
$a_id = $_SESSION["admin_id"];
?>
<link rel="stylesheet" href="sidebar_style.css">
<main class="main-content px-3 py-4" id="mainContent">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6">
            <form name="changepasswordform" class="p-4 bg-light border rounded" onsubmit="return validate()" method="POST">
                <?php if(isset($_GET["err"])){ echo $err;} ?>
                <h3 class="text-center">Change Password</h3>

                <div class="mb-3">
                    <input type="hidden" name="a_id" value="<?php echo $_SESSION["admin_id"]; ?>">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="curr_pass" value="" class="form-control w-100">
                </div>

                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_pass" value="" class="form-control w-100">
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="cnf_new_pass" value="" class="form-control w-100">
                </div>

                <div class="text-center my-3">
                    <input type="submit" value="submit" class="btn btn-primary btn-lg">
                </div>
            </form>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function validate(){
    const curr_pass = changepasswordform.curr_pass.value;
    const new_pass  = changepasswordform.new_pass.value;
    const cnf       = changepasswordform.cnf_new_pass.value;

    if(curr_pass == ""){
        alert("Enter Current Password");
        changepasswordform.curr_pass.focus();
        return false;
    }

    if(new_pass == ""){
        alert("Enter New Password");
        changepasswordform.new_pass.focus();
        return false;
    }

    if(cnf == ""){
        alert("Enter Confirm New Password");
        changepasswordform.cnf_new_pass.focus();
        return false;
    }

    if(new_pass != cnf){
        alert("New Password and Confirm New Password Should be Same");
        changepasswordform.cnf_new_pass.focus();
        return false;
    }
    changepasswordform.action="main.php?flag=11";
}
</script>
<?php include_once("end.php"); ?>
</body>
</html>