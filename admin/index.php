<?php
/**
 * Author : Salim Nazir
 * Module : JA Square Marketplace
 * FILENAME : admin/index.php
 * Path : /food_order_app/admin/index.php
 * Logic : Admin Dashboard & Authentication Gateway.
 */
require_once('../db_connect.php');
session_start();

// Handle Admin Login Logic
$error = "";
if (isset($_POST['admin_login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    // For now, using your fixed admin credentials
    if ($user === "admin" && $pass === "admin123") {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['terminal_user'] = "Salim Nazir"; // Sync with POS session
        $_SESSION['terminal_id'] = "01";
    } else {
        $error = "Unauthorized access. Invalid Admin credentials.";
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged_in']);
    header("Location: index.php");
    exit();
}

$is_logged_in = isset($_SESSION['admin_logged_in']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | JA Square Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { background: #f1f5f9; font-family: 'Inter', sans-serif; }
        .admin-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .stat-card { transition: transform 0.2s; border: none; border-radius: 15px; }
        .stat-card:hover { transform: translateY(-5px); }
        .login-box { max-width: 400px; margin-top: 100px; }
    </style>
</head>
<body>

<?php if (!$is_logged_in): ?>
    <div class="container">
        <div class="login-box mx-auto">
            <div class="card admin-card p-4">
                <div class="card-body text-center">
                    <div class="bg-dark d-inline-block p-3 rounded-circle mb-3">
                        <span class="material-icons text-white fs-1">admin_panel_settings</span>
                    </div>
                    <h4 class="fw-bold">Admin Portal</h4>
                    <p class="text-muted small mb-4">Secure access to inventory & settings</p>

                    <?php if ($error): ?>
                        <div class="alert alert-danger py-2 small"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3 text-start">
                            <label class="small fw-bold text-muted">ADMIN USER</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-4 text-start">
                            <label class="small fw-bold text-muted">SECRET KEY</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="admin_login" class="btn btn-dark w-100 py-2 fw-bold rounded-3">
                            VERIFY IDENTITY
                        </button>
                    </form>
                    <a href="../index.php" class="btn btn-link btn-sm mt-3 text-decoration-none text-muted">Back to POS</a>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">JA SQUARE ADMIN</a>
            <div class="d-flex">
                <a href="../index.php" class="btn btn-outline-light btn-sm me-2">Go to POS</a>
                <a href="?logout=1" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card stat-card p-4 bg-white shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted small fw-bold">TOTAL PRODUCTS</h6>
                            <?php 
                                $res = mysqli_query($conn, "SELECT COUNT(*) as total FROM {$table_prefix}products");
                                $count = mysqli_fetch_assoc($res)['total'];
                            ?>
                            <h2 class="fw-bold mb-0"><?php echo $count; ?></h2>
                        </div>
                        <span class="material-icons text-primary display-6">inventory_2</span>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card admin-card h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Management Console</h5>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <a href="manage-products.php" class="btn btn-outline-dark w-100 py-3 text-start px-3 rounded-4">
                                    <span class="material-icons align-middle me-2">edit_note</span> 
                                    Update Products
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="../profile.php" class="btn btn-outline-dark w-100 py-3 text-start px-3 rounded-4">
                                    <span class="material-icons align-middle me-2">settings</span> 
                                    Tax & System Settings
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

</body>
</html>