<?php
session_start();
print_r($_SESSION);
session_unset();
if(isset($_GET["p_id"])){
    $_SESSION["p_id"] = $_GET["p_id"];
}
if(isset($_GET["err"])){
    $err = $_GET["err"];
    if($err == 1){
        $err = "<h5 class='text-danger text-center'>Please Enter different Email/mobile Number...</h5>";
    }
    if($err == 2){
        $err = "<h5 class='text-danger text-center'>ERROR:Data was not Inserted in DB...</h5>";
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
    <title>Register</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row min-vh-100 justify-content-center align-items-center">
            <div class="col-12 d-flex justify-content-center align-items-center">
                <div class="box d-flex flex-column p-3 p-md-4 w-100">
                    <div class="bg-dark mb-3">
                        <h2 class="text-center text-white fw-bold p-3">Create an account</h2>
                    </div>

                    <div>
                        <form name="usersignupform" onsubmit="return validate()" method="POST">
                            <?php if(isset($_GET["err"])){ echo $err;} ?>

                            <input type="hidden" name="signup" value="true">

                            <div class="form-floating mb-3">
                                <input type="text" name="user_name" class="form-control" id="name" placeholder="Name" required>
                                <label for="name">Name</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" name="user_email" class="form-control" id="email" placeholder="Email" required>
                                <label for="email">Email</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" name="user_mobile" class="form-control" id="mobile" 
                                placeholder="Mobile Number" maxlength="10" onkeyup="validateMobile()" required>
                                <label for="mobile">Mobile Number</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" name="user_password" class="form-control" id="password" placeholder="Password" required>
                                <label for="password">Password</label>
                            </div>

                            <div class="form-floating mb-3">
                                <textarea name="user_address" class="form-control" id="address" 
                                placeholder="Enter full address" style="height: 100px" required></textarea>
                                <label for="address">Address</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" name="user_pincode" class="form-control" id="pincode" placeholder="Pincode" maxlength="6" required>
                                <label for="pincode">City Pincode</label>
                            </div>

                            <input type="submit" value="Register" class="btn btn-primary btn-lg fw-bold w-100 mb-3">

                            <div class="text-center">
                                <a href="customer_login.php" style="text-decoration:none;">
                                    Already have an account? Login
                                </a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
function validateMobile() {
    const mobileInput = document.getElementById("mobile");
    mobileInput.value = mobileInput.value.replace(/[^0-9]/g, '');
}

function validate(){
    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const mobile = document.getElementById("mobile").value.trim();
    const pass  = document.getElementById("password").value.trim();
    const address = document.getElementById("address").value.trim();
    const pincode = document.getElementById("pincode").value.trim();

    if(name === ""){
        alert("Enter Your Name");
        return false;
    }

    if(email === "" || !email.includes("@") || !email.includes(".")){
        alert("Enter valid email");
        return false;
    }

    if(mobile.length !== 10){
        alert("Mobile number must be exactly 10 digits");
        return false;
    }

    if(pass.length < 6){
        alert("Password must be at least 6 characters");
        return false;
    }

    if(address === ""){
        alert("Enter Your Address");
        return false;
    }

    if(pincode.length !== 6){
        alert("Pincode must be 6 digits");
        return false;
    }

    document.usersignupform.action = "send_otp.php"; //"admin/main.php?flag=12";
    return true;
}
</script>
</body>
</html>