<?php
/**
 * Author : Salim Nazir
 * Module : JA Square Marketplace
 * FILENAME : manage-products.php
 * Path : /food_order_app/admin/manage-products.php
 * Logic : CRUD operations for the 30-product inventory.
 */
require_once('../db_connect.php'); // Note the ../ to go up one folder
session_start();

// Simple Admin Check
if (!isset($_SESSION['terminal_user'])) {
    header("Location: ../login.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM {$table_prefix}products WHERE id = $id");
    header("Location: manage-products.php?msg=deleted");
}

// Handle Add New Product
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = (float)$_POST['price'];
    $cat = mysqli_real_escape_string($conn, $_POST['category']);
    $img = mysqli_real_escape_string($conn, $_POST['image']);

    $sql = "INSERT INTO {$table_prefix}products (product_name, product_price, product_category, product_image, product_status) 
            VALUES ('$name', '$price', '$cat', '$img', 'active')";
    mysqli_query($conn, $sql);
    header("Location: manage-products.php?msg=added");
}

$products = mysqli_query($conn, "SELECT * FROM {$table_prefix}products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Inventory | JA Square</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="../index.php">
            <span class="material-icons me-2">arrow_back</span> Inventory Manager
        </a>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Add New Product</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="small fw-bold">Product Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold">Price (₹)</label>
                            <input type="number" name="price" step="0.01" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold">Category</label>
                            <select name="category" class="form-select">
                                <option>Burgers</option>
                                <option>Pizza</option>
                                <option>Beverages</option>
                                <option>Desserts</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold">Image URL</label>
                            <input type="text" name="image" class="form-control" placeholder="https://source.unsplash.com/..." required>
                        </div>
                        <button type="submit" name="add_product" class="btn btn-primary w-100 fw-bold">SAVE PRODUCT</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Item</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($products)): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo $row['product_image']; ?>" class="rounded me-3" width="40" height="40" style="object-fit:cover;">
                                        <span class="fw-bold"><?php echo $row['product_name']; ?></span>
                                    </div>
                                </td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-secondary"><?php echo $row['product_category']; ?></span></td>
                                <td class="fw-bold text-primary">₹<?php echo number_format($row['product_price'], 2); ?></td>
                                <td class="text-end pe-4">
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Delete this item?')">
                                        <span class="material-icons fs-6">delete</span>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>