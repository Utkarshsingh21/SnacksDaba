<?php
include_once("start.php");
if(!isset($_SESSION["admin_id"])){
  header("location:admin_login.php");
  exit;
}
?>
<?php if (isset($_GET["msg"]) && $_GET["msg"] == 100){ ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo "Operation was Succesfull"; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
 <?php 
} ?>
<?php
include_once("end.php");
include_once("footer.php");
?>
 