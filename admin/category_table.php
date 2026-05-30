<?php
include_once("start.php");
include_once("conn.php");

$sql = "select * from product_category";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
    die("NO RECORD FOUND..");
}
if (isset($_GET["err"])) {
    $err = $_GET["err"];
    if ($err == 1) {
        $err = '<h4 style="color:red;" class="text-center mt-4">ERROR:MISSING PC_ID OR KEY VALUE..</h4>';
    } else if ($err == 2) {
        $err = '<h4 style="color:red;" class="text-center mt-4">ERROR:UNEXPECTED ERROR ENCOUNTERED..</h4>';
    } else if ($err == 3) {
        $err = '<h4 style="color:red;" class="text-center mt-4">ERROR:Some products are already assigned on this category. First delete all associated products.</h4>';
    }
}
$i = 1;
?>
<link rel="stylesheet" href="sidebar_style.css">
<main class="main-content px-3 mt-2" id="mainContent">
    <div style="margin:auto;">
        <?php if (isset($_GET["err"])) {
            echo $err;
        } ?>
        <h1 class="text-center fs-3 fw-bold mb-4">PRODUCT CATEGORY LIST</h1>
    </div>
    <div class="table-responsive" style="max-width:100%; margin: auto;">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Category Name</th>

                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr class="<?php if ($row["product_category_status"] == 'T') {echo "table-success";} else {echo "table-danger";} ?>">
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $row["product_category_name"]; ?></td>
                        <td><?php echo ($row["product_category_status"] == "T") ? "ENABLED" : "DISABLED"; ?></td>
                        <td style="width:110px; text-align:center; white-space:nowrap;">
                            <?php if ($row["product_category_status"] == "T") { ?>
                                <a href="main.php?flag=7&key=1&pc_id=<?php echo $row["pc_id"]; ?>" title="Disable" class="me-2">
                                    <i class="bi bi-x-circle text-danger"></i>
                                </a>
                            <?php } else { ?>
                                <a href="main.php?flag=7&key=0&pc_id=<?php echo $row["pc_id"]; ?>" title="Enable" class="me-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                </a>
                            <?php } ?>

                            <a href="product_category_edit.php?pc_id=<?php echo $row["pc_id"]; ?>" title="Edit" class="me-2">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <a href="main.php?flag=6&pc_id=<?php echo $row["pc_id"]; ?>" title="Delete">
                                <i class="bi bi-trash text-dark"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include_once("end.php"); ?>
</body>
</html>