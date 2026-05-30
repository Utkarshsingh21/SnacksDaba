<?php
$msg = $_GET["msg"] ?? "";
$err = $_GET["err"] ?? "";
?>

<?php if ($msg == 1): ?>
  <div class="alert alert-primary alert-dismissible fade show m-4" role="alert">
    Product was successfully added to the cart.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<?php if ($err == 45): ?>
  <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
    Product is already added to cart.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<?php include("first.php"); ?>
<?php include("nav_sidebar.php"); ?>

<div class="page-container">
  <?php include("search_results.php"); ?>
</div>

<?php include("footer.php"); ?>