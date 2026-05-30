<?php
session_start();
session_unset();
if(isset($_POST['signup']) && $_POST['signup'] == 'true'){
    $_SESSION['user_email'] = $_POST['user_email'];
    $_SESSION['user_name'] = $_POST['user_name'];
    $_SESSION['user_mobile'] = $_POST['user_mobile'];
    $_SESSION['user_password'] = $_POST['user_password'];
    $_SESSION['user_address'] = $_POST['user_address'];
    $_SESSION['user_pincode'] = $_POST['user_pincode'];
}
elseif(isset($_POST['forgotpassword']) && $_POST['forgotpassword'] == 'true'){
    $_SESSION['forgotpassword'] = 'true';
    $_SESSION['user_email'] = $_POST['user_email'];
    $_SESSION['user_name'] = "USER";
}

// -------- MAIL FUNCTION --------
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

function sendOTP($email, $name) {

    $otp = rand(100000, 999999);

    $_SESSION['sent_otp'] = $otp;
    $_SESSION['sent_otp_time'] = time();

    $mail = new PHPMailer(true);

    try{
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;

        $mail->Username   = 'us3457689@gmail.com';
        $mail->Password   = 'lerr smwb ztra vhzy';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('YOUR_EMAIL@gmail.com', 'SnacksDhaba');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = "OTP Verification";

        $mail->Body = "
        <h3>Hello $name</h3>
        <p>Your OTP is:</p>
        <h2>$otp</h2>
        <p><b>Valid for 60 seconds</b></p>
        ";

        $mail->send();
        return true;

    } catch(Exception $e){
        return false;
    }
}

// -------- SEND OTP --------
if(isset($_SESSION['user_email'])){
    print_r($_SESSION);
    sendOTP($_SESSION['user_email'], $_SESSION["user_name"]);
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
                        <h2 class="text-center text-white fw-bold p-3">Verify OTP</h2>
                    </div>

                    <div>
                    	<h2>Time left: <span id="timer">30</span> sec</h2>
                        <form name="otpform" onsubmit="return validate()" method="POST">
                            <?php if(isset($_GET["err"])){ echo $err;} ?>

                            <div class="form-floating mb-3">
                                <input type="number" name="user_otp" class="form-control" id="user_otp" placeholder="OTP recieved on email" required>
                                <label for="user_otp">Your OTP</label>
                            </div>

                            <input type="submit" value="Submit" class="btn btn-primary btn-lg fw-bold w-100 mb-3">
                        </form>
                        <button type="button" name="resend" onclick="resendotp();" class="btn btn-primary btn-lg fw-bold w-100 mb-3" id="resendbtn" disabled>Resend OTP</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
let timeLeft = 30;
let timer = document.getElementById("timer");
let resendBtn = document.getElementById("resendbtn");

let countdown = setInterval(function() {
    timeLeft--;
    timer.innerText = timeLeft;

    if (timeLeft <= 0) {
        clearInterval(countdown);
        timer.innerText = "Time up!";

        // 🔥 ENABLE BUTTON HERE
        resendBtn.disabled = false;
    }
}, 1000);
</script>
<script>
function validate(){
    const otp = document.getElementById("user_otp").value.trim();

    if(otp.length != 6){
        alert("please enter correct otp sent on your email");
        document.getElementById("user_otp").focus();
        return false;
    }

    document.otpform.action = "verify_otp.php";
    return true;
}

// 🔥 Resend button function
function resendotp(){
    location.reload();
}
</script>
</body>
</html>