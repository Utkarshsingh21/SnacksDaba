<?php
include("admin/conn.php");
$search = $_GET["searchbtn"] ?? "";

if ($search != "") {
    $sql = "SELECT * FROM product_detail 
            WHERE product_name LIKE '%$search%'
            ORDER BY product_add_dtime DESC";
} else {
    $sql = "SELECT * FROM product_detail 
            ORDER BY product_add_dtime DESC";
}

$result = mysqli_query($conn, $sql);
?>

<div class="row g-4">

<?php if(mysqli_num_rows($result) == 0){ ?>
    <div class="text-center mt-5">
        <h4 class="text-muted">No products found 😔</h4>
    </div>
<?php } else { ?>

<?php while ($row = mysqli_fetch_assoc($result)){ ?>

<div class="col-md-6 col-lg-3">
    <div class="product-card h-100">

        <div class="product-img-wrapper">
            <img src="<?= $row["product_image"]; ?>" class="product-img">
        </div>

        <div class="p-3 d-flex flex-column">

            <h6 class="fw-bold"><?= $row["product_name"]; ?></h6>

            <div class="mb-2 text-muted">
                ₹<?= $row["product_price"]; ?>
            </div>

            <div class="mt-auto d-flex gap-2">
                <form method="POST" action="ordernow.php" class="w-50">
                    <input type="hidden" name="p_id" value="<?= $row['p_id'] ?>">
                    <button class="btn btn-warning w-100 btn-sm">Buy</button>
                </form>

                <form method="POST" action="admin/main.php?flag=23" class="w-50">
                    <input type="hidden" name="p_id" value="<?= $row['p_id'] ?>">
                    <button class="btn btn-outline-primary w-100 btn-sm">Cart</button>
                </form>
            </div>

        </div>
    </div>
</div>

<?php } } ?>

</div>