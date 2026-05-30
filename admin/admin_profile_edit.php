<?php
include_once("start.php");
include_once("conn.php");
$a_id = $_SESSION['admin_id'];
$sql = "select * from admin_op where a_id = '$a_id'";
$result = mysqli_query($conn,$sql);
if(mysqli_num_rows($result) == 0){
	die("No records found");
}
$row = mysqli_fetch_assoc($result);
?>
<link rel="stylesheet" href="sidebar_style.css">
<main class="main-content px-3 py-4" id="mainContent">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6">
                <form name="editprofile" class="p-4 bg-light border rounded" onsubmit="return validate()" method="POST">
                    
                    <?php if (isset($_GET["err"]) && $_GET["err"] == 1){ ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo "Enter different email"; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php } ?>

                    <?php if (isset($_GET["err"]) && $_GET["err"] == 2){ ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo "ERROR: Change Was Not Successful"; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php } ?>

                    <h3 class="text-center mb-4 fw-bold">My Profile</h3>
                    
                    <input type="hidden" name="admin_id" value="<?php echo $a_id; ?>">

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="admin_name" class="form-control" value="<?php echo $row["name"]; ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" name="admin_email" class="form-control" value="<?php echo $row["email"]; ?>">
                    </div>

                    <div class="mb-3 text-center">
                        <input type="submit" value="Save" class="btn btn-primary btn-lg w-100">
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function validate(){
    const name = editprofile.admin_name.value;
    const email = editprofile.admin_email.value;

    const atIndex = email.indexOf('@');
    const dotIndex = email.lastIndexOf('.');

    if(name == ""){
        alert("Enter Your Name");
        editprofile.admin_name.focus();
        return false
    }

    if(email == ""){
        alert("Enter email");
        editprofile.admin_email.focus();
        return false;
    }

    if (atIndex < 1 || dotIndex <= atIndex + 1 || dotIndex === email.length - 1) {
        alert("Enter valid email");
        editprofile.admin_email.focus();
        return false;
    }
    editprofile.action="main.php?flag=19";
}
</script>

<?php include_once("end.php");?>
</body>
</html>