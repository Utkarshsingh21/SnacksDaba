<?php
include_once("simple_title.php");

$errorMsg = "";
if (isset($_GET["err"]) && $_GET["err"] == 1) {
    $errorMsg = "Enter valid email and password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f1f4f9;
}

.login-wrapper {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-card {
    width: 100%;
    max-width: 420px;
    border: none;
    border-radius: 14px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    padding: 30px;
    background: #fff;
}

.login-title {
    font-weight: 600;
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

.form-control {
    border-radius: 10px;
    padding: 10px 12px;
}

.btn-login {
    border-radius: 10px;
    padding: 12px;
    font-weight: 600;
}

.error-msg {
    background: #ffe6e6;
    color: #b30000;
    padding: 8px;
    border-radius: 8px;
    text-align: center;
    margin-bottom: 15px;
    font-size: 14px;
}
</style>
</head>

<body>

<div class="login-wrapper">

    <div class="login-card">

        <h3 class="login-title">Admin Login</h3>

        <?php if ($errorMsg != "") { ?>
            <div class="error-msg">
                <?= $errorMsg ?>
            </div>
        <?php } ?>

        <form name="loginform" method="POST" onsubmit="return validate()">

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="admin_email" class="form-control" placeholder="Enter email">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="admin_password" class="form-control" placeholder="Enter password">
            </div>

            <button type="submit" class="btn btn-primary btn-login w-100">
                Login
            </button>

        </form>

    </div>

</div>

<script>
function validate() {
    const email = loginform.admin_email.value.trim();
    const pass = loginform.admin_password.value.trim();

    const atIndex = email.indexOf('@');
    const dotIndex = email.lastIndexOf('.');

    if (email === "") {
        alert("Enter email for login");
        loginform.admin_email.focus();
        return false;
    }

    if (atIndex < 1 || dotIndex <= atIndex + 1 || dotIndex === email.length - 1) {
        alert("Enter valid email");
        loginform.admin_email.focus();
        return false;
    }

    if (pass === "") {
        alert("Enter password");
        loginform.admin_password.focus();
        return false;
    }

    loginform.action = "main.php?flag=9";
    return true;
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>