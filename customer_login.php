<?php
if(isset($_GET["err"])){
    $err = $_GET["err"];
    if($err == 1){
        $err = "<h5 class='text-danger text-center'>Please Enter correct Email/mobile Number and Password...</h5>";
    }
}
if(isset($_GET["msg"])){
    $msg = $_GET["msg"];
    if($msg == 100){
        $msg = "<h2 class='text-danger text-center'>Signup Was Succesfull..</h2>";
    }
    if($msg == 200){
        $msg = "<h2 class='text-danger text-center'>Password Was Succesfully Changed..</h2>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <title>Login</title>

    <style>

        /*body{
            background:linear-gradient(135deg, #667eea, #764ba2);
        }*/
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row min-vh-100 justify-content-center align-items-center">
            <div class="col-md-1"></div>
            <div class="col-md-10 d-flex justify-content-center align-items-center">
                
                <div class="box d-flex flex-column p-3 p-md-4 w-100">
                    
                    <div class="text-center bg-dark text-white mb-3 p-3">
                        <h4 class="fw-bold">Welcome Back! Please enter your details</h4>
                    </div>

                    <form name="customerloginform" onsubmit="return validate()" method="POST">
                        <?php if(isset($_GET["err"])){ echo $err;} ?>
                        <?php if(isset($_GET["msg"])){ echo $msg;} ?>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="user_email_mob" id="floatingInput" placeholder="name@example.com">
                            <label for="floatingInput">Email/Mobile No.</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" name="user_password" id="floatingPassword" placeholder="Password">
                            <label for="floatingPassword">Password</label>
                        </div>

                        <div class="mb-3 text-center">
                            <a href="forgotpassword.php" style="text-decoration:none; font-weight: bold;">
                                Forgot Password?
                            </a>
                        </div>
                        <div class="mb-3 text-center">
                            <a href="customer_signup.php" style="text-decoration:none; font-weight: bold;">
                                Don't have an account?
                            </a>
                        </div>

                        <input type="submit" value="Enter" class="btn btn-success fw-bold btn-lg w-100">
                    
                    </form>
                </div>
            </div>
            <div class="col-md-1"></div>

        </div>
    </div>

<script>
function validate(){
    const email_mob = document.customerloginform.user_email_mob.value;
    const pass  = document.customerloginform.user_password.value;

    if(email_mob == ""){
        alert("Enter email/mobile for login");
        document.customerloginform.user_email_mob.focus();
        return false;
    }

    if(pass == ""){
        alert("Enter Password");
        document.customerloginform.user_password.focus();
        return false;
    }

    document.customerloginform.action = "admin/main.php?flag=13";
    return true;
}
</script>

</body>
</html>