<?php
/**
 * Author : Salim Nazir
 * Module : JA Square Marketplace
 * FILENAME : admin/dashboard.php
 * Path : /food_order_app/admin/dashboard.php
 * Logic : Visual overview of the POS system and quick management links.
 */
require_once('../db_connect.php');
session_start();

// Security: Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit();
}

// 1. Fetch Stats
$total_prods = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM {$table_prefix}products"))['total'];
$total_cats  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT product_category) as total FROM {$table_prefix}products"))['total'];
$inv_value   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(product_price) as total FROM {$table_prefix}products"))['total'];

// 2. Fetch Recent 5 Products
$recent_prods = mysqli_query($conn, "SELECT * FROM {$table_prefix}products ORDER BY id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | JA Square Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        :root { --admin-bg: #f4f7fe; --admin-card-shadow: 0 10px 30px rgba(165, 175, 197, 0.1); }
        body { background: var(--admin-bg); font-family: 'Inter', sans-serif; color: #2d3748; }
        .stat-card { border: none; border-radius: 20px; box-shadow: var(--admin-card-shadow); transition: 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .icon-box { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .nav-link-admin { border-radius: 12px; transition: 0.2s; color: #718096; font-weight: 500; }
        .nav-link-admin:hover, .nav-link-admin.active { background: #fff; color: #3b82f6; box-shadow: var(--admin-card-shadow); }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-white min-vh-100 p-4 sticky-top">
            <h5 class="fw-bold mb-5 text-primary">JA SQUARE</h5>
            <ul class="nav flex-column gap-2">
                <li class="nav-item">
                    <a class="nav-link nav-link-admin active p-3 d-flex align-items-center" href="dashboard.php">
                        <span class="material-icons me-2">dashboard</span> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-admin p-3 d-flex align-items-center" href="manage-products.php">
                        <span class="material-icons me-2">inventory_2</span> Products
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-admin p-3 d-flex align-items-center" href="../profile.php">
                        <span class="material-icons me-2">settings</span> Settings
                    </a>
                </li>
                <li class="nav-item mt-5">
                    <a class="nav-link text-danger p-3 d-flex align-items-center" href="index.php?logout=1">
                        <span class="material-icons me-2">logout</span> Logout
                    </a>
                </li>
            </ul>
        </nav>

        <main class="col-md-10 p-5">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-bold mb-0">System Overview</h2>
                    <p class="text-muted">Welcome back, Admin. Here is what's happening.</p>
                </div>
                <a href="../index.php" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm fw-bold">Open POS Terminal</a>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card stat-card p-4">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                                <span class="material-icons">shopping_bag</span>
                            </div>
                            <div>
                                <small class="text-muted d-block fw-bold">Total Items</small>
                                <h3 class="fw-bold mb-0"><?php echo $total_prods; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card p-4">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                                <span class="material-icons">category</span>
                            </div>
                            <div>
                                <small class="text-muted d-block fw-bold">Active Categories</small>
                                <h3 class="fw-bold mb-0"><?php echo $total_cats; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card p-4">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-warning bg-opacity-10 text-warning me-3">
                                <span class="material-icons">payments</span>
                            </div>
                            <div>
                                <small class="text-muted d-block fw-bold">Stock Value</small>
                                <h3 class="fw-bold mb-0">₹<?php echo number_format($inv_value, 2); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-bold mb-0">Recently Added Products</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-muted small">
                                    <th class="ps-4">PRODUCT</th>
                                    <th>CATEGORY</th>
                                    <th>PRICE</th>
                                    <th class="text-end pe-4">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($p = mysqli_fetch_assoc($recent_prods)): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo $p['product_image']; ?>" class="rounded-3 me-3" width="40" height="40" style="object-fit:cover;">
                                            <span class="fw-bold"><?php echo $p['product_name']; ?></span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark rounded-pill px-3"><?php echo $p['product_category']; ?></span></td>
                                    <td class="fw-bold text-primary">₹<?php echo number_format($p['product_price'], 2); ?></td>
                                    <td class="text-end pe-4">
                                        <a href="manage-products.php" class="btn btn-sm btn-light rounded-circle">
                                            <span class="material-icons fs-6">edit</span>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>