<?php
/**
 * FILENAME : public-header.php
 * Logic : Minimalist header for public-facing dashboard.
 */
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Security: Still check if terminal is initialized
if (!isset($_SESSION['terminal_user'])) {
    header("Location: index.php");
    exit();
}
require_once('db_connect.php');

// Fetch Settings for Business Name
$settings_query = mysqli_query($conn, "SELECT * FROM {$table_prefix}settings WHERE setting_key = 'business_name'");
$biz_row = mysqli_fetch_assoc($settings_query);
$biz_name = $biz_row ? $biz_row['setting_value'] : "JA Square";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $biz_name; ?> | Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        :root { --luxury-gold: #d4af37; }
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
        .public-nav { background: #fff; border-bottom: 1px solid #eee; height: 80px; }
        .brand-logo { color: #000; text-decoration: none; font-weight: 800; letter-spacing: 1px; }
    </style>
    <script>
        const POS_CONFIG = { taxRate: 1, currency: '₹' }; // Hardcoded for public view
    </script>
</head>
<body>

<nav class="public-nav d-flex align-items-center px-4 justify-content-between">
    <a href="dashboard.php" class="brand-logo fs-4">
        <span style="color:var(--luxury-gold);">JA</span> SQUARE
    </a>
    
    <div class="d-none d-md-block w-50">
        <form action="dashboard.php" method="GET" class="position-relative">
            <span class="material-icons position-absolute top-50 translate-middle-y ms-3 text-muted">search</span>
            <input type="text" name="search" class="form-control rounded-pill border-0 bg-light ps-5 py-2" placeholder="Search our delicacies...">
        </form>
    </div>

    <div class="text-end">
        <small class="text-muted d-block" style="font-size: 10px;">GUEST VIEW</small>
        <span class="fw-bold small">Terminal #<?php echo $_SESSION['terminal_id']; ?></span>
    </div>
</nav>