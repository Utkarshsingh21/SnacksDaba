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
$sql = "select pc_id,product_category_name from product_category";
$result = mysqli_query($conn,$sql);


?>
<link rel="stylesheet" href="sidebar_style.css">
<main class="main-content px-3 py-4" id="mainContent">
    <form 
        name="addproductform" 
        class="container p-4" 
        onsubmit="return validate()" 
        enctype="multipart/form-data"  
        method="POST"
    >
        <!-- Alert Messages -->
        <?php if (isset($_GET["err"])) { echo $err; } ?> 
        <?php if (isset($_GET["msg"])) { echo $msg; } ?> 

        <!-- Form Heading -->
        <div class="mb-5 text-center">
            <h1 class="text-primary fs-2 fw-bold">Add Product</h1>
        </div>

        <!-- Product Category -->
        <div class="mb-4 row">
            <div class="col-12 col-sm-12 col-md-12">
                <label for="p_category" class="form-label fs-5">Select Product Category:</label>
                <select name="p_category" id="p_category" class="form-select fs-6 border-info">
                    <option value="">-- Select Category --</option>
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                        <option value="<?php echo $row["pc_id"]; ?>">
                            <?php echo ($row["product_category_name"]); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <!-- Product Name -->
        <div class="mb-4 row">
            <div class="col-12 col-sm-12 col-md-12">
                <label for="p_name" class="form-label fs-5">Product Name:</label>
                <input type="text" name="p_name" id="p_name" class="form-control fs-6 border-secondary">
            </div>
        </div>

        <!-- Product Description -->
        <div class="mb-4 row">
            <div class="col-12 col-sm-12 col-md-12">
                <label for="p_descrip" class="form-label fs-5">Product Description:</label>
                <textarea name="p_descrip" id="p_descrip" rows="4" class="form-control fs-6 border-warning"></textarea>
            </div>
        </div>

        <!-- Product Price -->
        <div class="mb-4 row">
            <div class="col-12 col-sm-12 col-md-12">
                <label for="p_price" class="form-label fs-5">Product Price:</label>
                <input type="text" name="p_price" id="p_price" class="form-control fs-6 border-success">
            </div>
        </div>

        <!-- Product Weight -->
        <div class="mb-4 row">
            <div class="col-12 col-sm-12 col-md-12">
                <label for="p_weight" class="form-label fs-5">Product Weight:</label>
                <div class="input-group">
                    <input type="text" name="p_weight" id="p_weight" class="form-control fs-6 border-danger">
                    <select name="p_unit" class="form-select w-auto fs-6 border-primary">
                        <option value="kg">kg</option>
                        <option value="gram">gram</option>
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
                <input type="file" name="p_image" id="p_image" class="form-control fs-6 border-black">
            </div>
        </div>

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
    const category = addproductform.p_category.value;
    const name    = addproductform.p_name.value;
    const descrip = addproductform.p_descrip.value;
    const price   = addproductform.p_price.value;
    const weight  = addproductform.p_weight.value;


    if(category == ""){
        alert("please SELECT Product Category");
        addproductform.p_category.focus();
        return false;
    }
    if(name == ""){
        alert("please Enter Product Name");
        addproductform.p_name.focus();
        return false;
    }
    if(descrip == ""){
        alert("please Enter Product Description");
        addproductform.p_name.focus();
        return false;
    }
    if(price == ""){
        alert("please Enter Product Price");
        addproductform.p_price.focus();
        return false;
    }
    if(weight == ""){
        alert("please Enter Product Package Weight");
        addproductform.p_weight.focus();
        return false;
    }
    addproductform.action="main.php?flag=2";
}
</script>

<?php include("end.php");?>
</body>
</html>