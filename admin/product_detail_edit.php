<?php
include_once("start.php");
include_once("conn.php");
if(isset($_GET["err"])){
    $err = $_GET["err"];
    if($err == 1){
        $err = '<h4 style="color:red;">ERROR:INCORRECT TYPE OF FILE WAS UPLOADED..</h4>';
    }
    if($err == 2){
        $err = '<h4 style="color:red;">ERROR:MAX SIZE OF FILE ALLOWED IS 5MB..</h4>';
    }
    if($err == 3){
        $err = '<h4 style="color:red;">ERROR:UNEXPECTED ERROR ENCOUNTERED..</h4>';
    }
}
if(isset($_GET["msg"])){
    $msg = $_GET["msg"];
    if($msg == 1){
        $msg = '<h4 style="color:blue;">PRODUCT DETAILS WAS SUCCESSFULLY INSERTED IN DB..</h4>';
    }
}
if(isset($_GET["p_id"])){
    $p_id = $_GET["p_id"];
    $sql = "select * from product_detail where p_id = '$p_id'";
    $result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)== 1){
        $row = mysqli_fetch_assoc($result);
        $product_category    = $row["pc_id"];
        $product_name        = $row["product_name"];
        $product_descrip     = $row["product_description"];
        $product_status      = $row["product_status"];
        $product_image       = $row["product_image"];
        $product_price       = $row["product_price"];
        $product_weight_qty  = $row["product_weight_qty"];
        $product_weight_unit = $row["product_weight_unit"];
    }
}
$sql2 = "select pc_id,product_category_name from product_category";
$result2 = mysqli_query($conn,$sql2);
?>
<link rel="stylesheet" href="sidebar_style.css">
<main class="main-content px-3 py-4" id="mainContent">
    <form 
        name="editform" 
        class="container p-4 bg-light border rounded" 
        onsubmit="return validate()" 
        enctype="multipart/form-data"  
        method="POST"
    >
        <!-- Alert Messages -->
        <?php if (isset($_GET["err"])) { echo $err; } ?> 
        <?php if (isset($_GET["msg"])) { echo $msg; } ?> 

        <!-- Form Heading -->
        <div class="mb-5 text-center">
            <h1 class="text-primary fs-2 fw-bold">Edit Product Details</h1>
        </div>

        <!-- Product Category -->
        <div class="mb-4 row">
            <div class="col-12 col-sm-12 col-md-12">
                <input type="hidden" name="p_id" value="<?php echo $p_id; ?>">
                <label for="p_category" class="form-label fs-5">Select Product Category:</label>
                <select name="p_category" id="p_category" class="form-select fs-6">
                    <option value="">-- Select Category --</option>
                    <?php while($row = mysqli_fetch_assoc($result2)) { ?>
                        <option value = "<?php echo $row["pc_id"]; ?>"<?php if(isset($product_category) && $product_category == $row["pc_id"])echo "selected";?>><?php echo $row["product_category_name"]; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <!-- Product Name -->
        <div class="mb-4 row">
            <div class="col-12 col-sm-12 col-md-12">
                <label for="p_name" class="form-label fs-5">Product Name:</label>
                <input type="text" name="p_name" id="p_name" class="form-control fs-6" value="<?php echo $product_name; ?>">
            </div>
        </div>

        <!-- Product Description -->
        <div class="mb-4 row">
            <div class="col-12 col-sm-12 col-md-12">
                <label for="p_descrip" class="form-label fs-5">Product Description:</label>
                <textarea name="p_descrip" id="p_descrip" rows="4" class="form-control fs-6"><?php echo $product_descrip; ?></textarea>
            </div>
        </div>

        <!-- Product Price -->
        <div class="mb-4 row">
            <div class="col-12 col-sm-12 col-md-12">
                <label for="p_price" class="form-label fs-5">Product Price:</label>
                <input type="text" name="p_price" id="p_price" class="form-control fs-6"  value="<?php echo $product_price; ?>">
            </div>
        </div>

        <!-- Product Weight -->
        <div class="mb-4 row">
            <div class="col-12 col-sm-12 col-md-12">
                <label for="p_weight" class="form-label fs-5">Product Weight:</label>
                <div class="input-group">
                    <input type="text" name="p_weight" id="p_weight" class="form-control fs-6" value="<?php echo $product_weight_qty; ?>">
                    <select name="p_unit" class="form-select w-auto fs-6">
                        <option value="kg" <?php if(isset($product_weight_unit) && $product_weight_unit == "kg")echo"selected";?>>kg</option>
                            <option value="gram" <?php if(isset($product_weight_unit) && $product_weight_unit == "gram")echo"selected";?>>gram</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Product Image -->
        <div class="mb-4 row">
            <div class="col-12 col-sm-12 col-md-12">
                <label for="p_image" class="form-label fs-5">
                    Product Image:
                    <small class="text-danger d-block fs-6">(Accepted: jpg, jpeg, png | Max Size: 5MB)</small>
                </label>
                <input type="file" name="p_image" id="p_image" class="form-control fs-6">
            </div>
        </div>
        
        <?php if (!empty($product_image)){ ?>
            <div class="row mb-4">
                <div class="col-12 col-sm-12 col-md-12 text-center">
                    <a href="<?php echo $product_image; ?>" target="_blank" class="btn btn-success">View</a>
                </div>
            </div>
        <?php } ?>



        <!-- Submit Button -->
        <div class="row mb-4">
            <div class="col-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary fs-5">Submit</button>
            </div>
        </div>
    </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function validate(){
            const category = editform.p_category.value;
            const name    = editform.p_name.value;
            const descrip = editform.p_descrip.value;
            const price   = editform.p_price.value;
            const weight  = editform.p_weight.value;


            if(category == ""){
                alert("please SELECT Product Category");
                editform.p_category.focus();
                return false;
            }
            if(name == ""){
                alert("please Enter Product Name");
                editform.p_name.focus();
                return false;
            }
            if(descrip == ""){
                alert("please Enter Product Description");
                editform.p_name.focus();
                return false;
            }
            if(price == ""){
                alert("please Enter Product Price");
                editform.p_price.focus();
                return false;
            }
            if(weight == ""){
                alert("please Enter Product Package Weight");
                editform.p_weight.focus();
                return false;
            }
            editform.action="main.php?flag=3";
        }
    </script>
<?php include_once("end.php"); ?>
</body>
</html>