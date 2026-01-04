<?php
/**
 * Author : Salim Nazir
 * Email : salnazi@gmail.com
 * Powered By : JA Square
 * Module : JA Square Marketplace
 * FILENAME : menu.php
 * Path : /food_order_app/menu.php
 * Logic : Master Menu Catalog with searching and status management.
 */
require_once('db_connect.php');
include('includes/header.php');

// Fetch all 30 products with category names
$query = "SELECT p.*, c.cat_name 
          FROM {$table_prefix}products p 
          LEFT JOIN {$table_prefix}categories c ON p.cat_id = c.id 
          ORDER BY c.cat_name ASC, p.product_name ASC";
$result = mysqli_query($conn, $query);
?>

<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="fw-bold mb-0">Master Catalog</h3>
            <p class="text-muted small">Manage your 30 active menu items and pricing.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <button class="btn btn-primary shadow-sm px-4 rounded-pill" onclick="posNotify('Add New Feature Coming Soon', 'info')">
                <span class="material-icons align-middle me-1">add</span> New Product
            </button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 rounded-4">
                <small class="text-muted fw-bold text-uppercase">Total Items</small>
                <h2 class="fw-bold mb-0">30</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 rounded-4">
                <small class="text-success fw-bold text-uppercase">Vegetarian</small>
                <h2 class="fw-bold mb-0">24</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 rounded-4">
                <small class="text-primary fw-bold text-uppercase">Categories</small>
                <h2 class="fw-bold mb-0">5</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 rounded-4">
                <small class="text-warning fw-bold text-uppercase">Out of Stock</small>
                <h2 class="fw-bold mb-0">0</h2>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="row g-2">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <span class="material-icons">search</span>
                        </span>
                        <input type="text" id="catalogSearch" class="form-control border-start-0 ps-0" placeholder="Filter by name, category or tag...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select" id="categoryFilter">
                        <option value="all">All Categories</option>
                        <option>Healthy Bowls</option>
                        <option>Italian Classics</option>
                        <option>Gourmet Pastas</option>
                        <option>Signature Sweets</option>
                        <option>Refreshments</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Product</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Category</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Diet</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Price</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Status</th>
                        <th class="pe-4 py-3 text-center text-uppercase small fw-bold text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody id="catalogBody">
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="catalog-row" data-cat="<?php echo $row['cat_name']; ?>">
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <img src="<?php echo $row['image_url']; ?>" class="rounded-3 me-3" width="45" height="45" style="object-fit: cover;">
                                <div>
                                    <h6 class="fw-bold mb-0 item-name"><?php echo $row['product_name']; ?></h6>
                                    <small class="text-muted"><?php echo $row['meta_tags']; ?></small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?php echo $row['cat_name']; ?></span></td>
                        <td>
                            <?php if($row['is_veg']): ?>
                                <span class="material-icons text-success fs-5" title="Veg">eco</span>
                            <?php else: ?>
                                <span class="material-icons text-danger fs-5" title="Non-Veg">layers</span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold text-primary">₹<?php echo number_format($row['price'], 2); ?></td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-switch form-check-input" type="checkbox" checked>
                            </div>
                        </td>
                        <td class="pe-4 text-center">
                            <button class="btn btn-sm btn-light rounded-pill px-3" onclick="posNotify('Edit Mode Active', 'success')">Edit</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
/**
 * Catalog Search & Filter
 */
document.getElementById('catalogSearch').addEventListener('input', function(e) {
    let val = e.target.value.toLowerCase();
    filterCatalog();
});

document.getElementById('categoryFilter').addEventListener('change', filterCatalog);

function filterCatalog() {
    let search = document.getElementById('catalogSearch').value.toLowerCase();
    let cat = document.getElementById('categoryFilter').value;
    let rows = document.querySelectorAll('.catalog-row');

    rows.forEach(row => {
        let name = row.querySelector('.item-name').innerText.toLowerCase();
        let rowCat = row.getAttribute('data-cat');
        
        let matchSearch = name.includes(search);
        let matchCat = (cat === 'all' || rowCat === cat);

        if(matchSearch && matchCat) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

<?php 
include('includes/mobile_menu_nav.php'); 
include('includes/footer.php'); 
?>