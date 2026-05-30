<?php
session_start();

if (!isset($_SESSION['sent_otp'])) {
    header("Location: otp_page.php?expired=1");
    exit();
}

// expiry check
if ((time() - $_SESSION['sent_otp_time']) > 60) {
    header("Location: otp_page.php?expired=1");
    exit();
}

// match
if ($_POST['user_otp'] == $_SESSION['sent_otp']) {
    if($_SESSION['forgotpassword'] == 'true'){
        header("Location: newpassword.php");
    }
    else{
        header("Location: admin/main.php?flag=12");
    }

} else {
    header("Location: otp_page.php?invalid=1");
}
?>