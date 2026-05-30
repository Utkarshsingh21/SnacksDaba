<?php
include_once("start.php");
include_once("conn.php");
if(isset($_GET["pc_id"])){
    $pc_id = $_GET["pc_id"];
    $sql = "select * from product_category where pc_id = '$pc_id'";
    $result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)== 1){
        $row = mysqli_fetch_assoc($result);
        $category_name = $row["product_category_name"];
    }
}

if(isset($_GET["msg"])){
    $msg = $_GET["msg"];
    if($msg == 1){
        $msg = '<h4 style="color:blue;">CATEGORY DETAILS WAS SUCCESSFULLY INSERTED IN DB..</h4>';
    }
}
?>

<link rel="stylesheet" href="sidebar_style.css">
<!-- Main Content -->
<main class="main-content px-3 py-4" id="mainContent" style="min-height: 100vh; margin-top:20vh;">
    <div class="container h-100">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <form name="addcategoryform" class="p-4 bg-light border rounded shadow-sm" onsubmit="return validate()" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Product Category:</label>
                        <input type="text" name="p_category" class="form-control" value="<?php echo $category_name;?>">
                        <input type="hidden" name="pc_id" value="<?php echo $pc_id; ?>">
                    </div>
                    <div>
                        <input type="submit" value="Add" id="addbtn" class="btn btn-primary w-100" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function validate(){
    const p_category = addcategoryform.p_category.value;

    if(p_category == ""){
        alert("Please Enter the product Category..");
        addcategoryform.p_category.focus();
        return false;
    }
    addcategoryform.action="main.php?flag=8";
    return true;
}
</script>
<?php include_once("end.php");?>
</body>
</html>