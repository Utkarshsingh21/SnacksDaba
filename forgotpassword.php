<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <title>Forgot Password</title>

</head>

<body>
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card p-4 shadow" style="width:400px;">
        <h4 class="text-center mb-3">Forgot Password</h4>

        <form action="send_otp.php" method="POST">
            <div class="form-floating mb-3">
                <input type="hidden" name="forgotpassword" value="true">
                <input type="text" class="form-control" name="user_email" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Email</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Send OTP
            </button>
        </form>
    </div>
</div>