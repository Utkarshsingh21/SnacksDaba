<?php
include_once("start.php");

$feedback = "";
if(isset($_GET["err"]) && $_GET["err"] == 1){
    $feedback = '<div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
      Product Cateory was not added..
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
if(isset($_GET["msg"]) && $_GET["msg"] == 1){
    $feedback = '<div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
      Product Cateory was added Successfully..
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="sidebar_style.css">
<style>
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', sans-serif;
    position: relative;
  }

  .bg-image {
    background-image: url('https://images.unsplash.com/photo-1542744173-05336fcc7ad4?auto=format&fit=crop&w=1950&q=80');
    background-size: cover;
    background-position: center;
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    z-index: -1;
  }

  .form-container {
    max-width: 500px;
    margin: 100px auto;
    position: relative;
    z-index: 1;
  }

  .card {
    background-color: rgba(255, 255, 255, 0.95); /* Slight transparency */
    border-radius: 15px;
    box-shadow: 0 0 15px rgba(0,0,0,0.3);
  }
</style>

<div class="bg-image"></div>
<div class="container-fluid">
  <main class="main-content px-3" id="mainContent">
    <?php echo $feedback; ?>
    <div class="form-container">
      <div class="card shadow-lg p-4">
        <h4 class="card-title text-center mb-4">Add Product Category</h4>
        <form name="addcategoryform" onsubmit="return validate()" method="POST">
          <div class="mb-3">
            <label class="form-label">Product Category</label>
            <input type="text" name="p_category" class="form-control" placeholder="Enter product category">
          </div>
          <div>
            <input type="submit" value="Add Category" id="addbtn" class="btn btn-primary w-100">
          </div>
        </form>
      </div>
    </div>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function validate(){
    const p_category = addcategoryform.p_category.value.trim();

    if(p_category === ""){
        alert("⚠️ Please enter the product category.");
        addcategoryform.p_category.focus();
        return false;
    }
    addcategoryform.action="main.php?flag=1";
}
</script>

<?php include_once("end.php"); ?>
</body>
</html>
