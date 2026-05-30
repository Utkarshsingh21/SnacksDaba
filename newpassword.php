<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }
        .card {
            border-radius: 15px;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #764ba2;
        }
        .strength {
            font-size: 13px;
        }
    </style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card p-4 shadow-lg" style="width:400px;">
        
        <h3 class="text-center mb-3 text-primary">Reset Password</h3>

        <form action="admin/main.php?flag=31" method="POST" onkeyup="validateForm()">
            <input type="hidden" name="user_email" value="<?php echo $_SESSION['user_email']; ?>">
            <!-- New Password -->
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <div id="strengthMsg" class="strength text-muted"></div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" id="confirm_password" class="form-control" required>
                <div id="matchMsg" class="strength"></div>
            </div>

            <!-- Submit -->
            <button id="submitBtn" class="btn btn-danger w-100" disabled>
                Update Password
            </button>

        </form>
    </div>
</div>

<script>
function validateForm() {
    let pass = document.getElementById("password").value;
    let confirm = document.getElementById("confirm_password").value;
    let strengthMsg = document.getElementById("strengthMsg");
    let matchMsg = document.getElementById("matchMsg");
    let btn = document.getElementById("submitBtn");

    let strongPattern = /^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W]).{6,}$/;

    // Password strength
    if(pass.length === 0){
        strengthMsg.innerHTML = "";
    }
    else if(!strongPattern.test(pass)){
        strengthMsg.innerHTML = "Weak password (Use uppercase, number & symbol)";
        strengthMsg.className = "strength text-danger";
    } else {
        strengthMsg.innerHTML = "Strong password";
        strengthMsg.className = "strength text-success";
    }

    // Match validation
    if(confirm.length === 0){
        matchMsg.innerHTML = "";
    }
    else if(pass !== confirm){
        matchMsg.innerHTML = "Passwords do not match";
        matchMsg.className = "strength text-danger";
    } else {
        matchMsg.innerHTML = "Passwords match";
        matchMsg.className = "strength text-success";
    }

    // Enable button only if valid
    if(strongPattern.test(pass) && pass === confirm){
        btn.disabled = false;
    } else {
        btn.disabled = true;
    }
}
</script>
</body>
</html>