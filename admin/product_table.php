<?php
include_once("start.php");
include_once("conn.php");

if(isset($_GET["err"])){
    $err = $_GET["err"];
    if($err == 1){
        $err='<h4 style="color:red;">ERROR:MISSING PC_ID OR KEY VALUE..</h4>';
    }
    if($err == 2){
        $err='<h4 style="color:red;">ERROR:UNEXPECTED ERROR ENCOUNTERED..</h4>';
    }
}

$sql = "select t1.p_id,t1.product_name,t1.product_description,t1.product_price,t1.product_weight_qty,t1.product_weight_unit,product_image,t1.product_status,t2.product_category_name from product_detail t1 inner join product_category t2 on t1.pc_id = t2.pc_id order by t1.product_add_dtime desc";
$result = mysqli_query($conn,$sql);
if(mysqli_num_rows($result) == 0){
    die("NO RECORD FOUND..");
}
$i = 1;
?>
<link rel="stylesheet" href="sidebar_style.css">
<style>
body {
    background-color: #f5f7fa;
}

/* Navbar */
.navbar {
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

/* Card Styling */
.product-card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    transition: 0.3s;
}

.product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

/* Image */
.product-img {
    height: 200px;
    object-fit: cover;
}

/* Footer buttons spacing */
.card-footer .btn {
    flex: 1;
    font-size: 13px;
}
</style>


<!-- MAIN CONTENT -->
<div class="container mt-4">

    <?php if(isset($_GET["err"])){echo $err;} ?>

    <div class="row g-4">

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

        <div class="col-sm-6 col-md-4 col-lg-3">

            <div class="card product-card h-100 shadow-sm">

    <!-- IMAGE -->
    <?php if (!empty($row["product_image"])) { ?>
        <img src="<?php echo $row["product_image"]; ?>" class="product-img w-100">
    <?php } else { ?>
        <div class="d-flex align-items-center justify-content-center bg-light product-img">
            <i class="bi bi-image fs-2 text-muted"></i>
        </div>
    <?php } ?>

    <div class="card-body">

        <!-- Category -->
        <span class="badge bg-secondary mb-2">
            <i class="bi bi-tag"></i>
            <?php echo $row["product_category_name"]; ?>
        </span>

        <!-- Name -->
        <h6 class="fw-bold mb-1">
            <?php echo $row["product_name"]; ?>
        </h6>

        <!-- Description -->
        <p class="text-muted small mb-2">
            <?php echo substr($row["product_description"],0,60)."..." ?>
        </p>

        <!-- Price -->
        <div class="fw-bold fs-6">
            <i class="bi bi-currency-rupee"></i>
            <?php echo $row["product_price"]; ?>
        </div>

        <!-- Weight -->
        <small class="text-muted">
            <i class="bi bi-box-seam"></i>
            <?php echo $row["product_weight_qty"]." ".$row["product_weight_unit"]; ?>
        </small>

        <!-- Status -->
        <div class="mt-2">
            <?php if($row["product_status"] == "T"){ ?>
                <span class="badge bg-success">
                    <i class="bi bi-check-circle"></i> Active
                </span>
            <?php } else { ?>
                <span class="badge bg-danger">
                    <i class="bi bi-x-circle"></i> Inactive
                </span>
            <?php } ?>
        </div>

    </div>

    <!-- ACTIONS -->
    <div class="card-footer bg-white d-flex gap-1">

        <?php if($row["product_status"] == "T"){ ?>
            <a href="main.php?flag=5&key=1&p_id=<?php echo $row["p_id"];?>" 
               class="btn btn-sm btn-outline-danger"
               title="Disable">
               <i class="bi bi-x-circle"></i>
            </a>
        <?php } else { ?>
            <a href="main.php?flag=5&key=0&p_id=<?php echo $row["p_id"];?>" 
               class="btn btn-sm btn-outline-success"
               title="Enable">
               <i class="bi bi-check-circle"></i>
            </a>
        <?php } ?>

        <a href="product_detail_edit.php?p_id=<?php echo $row["p_id"];?>" 
           class="btn btn-sm btn-outline-primary"
           title="Edit">
           <i class="bi bi-pencil"></i>
        </a>

        <a href="main.php?flag=4&p_id=<?php echo $row["p_id"];?>" 
           class="btn btn-sm btn-outline-dark"
           title="Delete"
           onclick="return confirm('Delete this product?');">
           <i class="bi bi-trash"></i>
        </a>

    </div>

</div>

        </div>

    <?php } ?>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include_once("end.php");?>
</body>
</html>