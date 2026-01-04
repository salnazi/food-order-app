<?php
/**
 * Author : Salim Nazir
 * Module : JA Square Marketplace
 * FILENAME : login.php
 * Logic : Secure terminal access with session management.
 */
require_once('db_connect.php');
session_start();

// Redirect if already logged in
if (isset($_SESSION['terminal_user'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; // In production, use password_verify()

    // Simple auth for Terminal 01 (You can expand this with a users table later)
    if ($username === "admin" && $password === "admin123") {
        $_SESSION['terminal_user'] = "Salim Nazir";
        $_SESSION['terminal_id'] = "01";
        header("Location: index.php");
    } else {
        $error = "Invalid credentials. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | JA Square Marketplace POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            background: #f8fafc;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .login-card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 400px;
        }
        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            background-color: #f1f5f9;
            border: 2px solid transparent;
        }
        .form-control:focus {
            background-color: #fff;
            border-color: #3b82f6;
            box-shadow: none;
        }
        .btn-login {
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            background: #3b82f6;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<div class="container p-3">
    <div class="login-card card mx-auto">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <div class="bg-primary bg-opacity-10 d-inline-block p-3 rounded-circle mb-3">
                    <span class="material-icons text-primary fs-1">point_of_sale</span>
                </div>
                <h4 class="fw-bold">Terminal Login</h4>
                <p class="text-muted small">JA Square Marketplace POS v2.0</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger border-0 small py-2 rounded-3 text-center">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">USERNAME</label>
                    <input type="text" name="username" class="form-control" placeholder="admin" required>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">PASSWORD</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 btn-login shadow-sm">
                    ACCESS TERMINAL
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="text-muted" style="font-size: 11px;">
                    &copy; 2025 JA Square. All rights reserved.<br>
                    Authorized Personnel Only.
                </p>
            </div>
        </div>
    </div>
</div>

</body>
</html>