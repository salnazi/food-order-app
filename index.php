<?php
/**
 * FILENAME : index.php
 * VERSION  : Final Merged M3 Product Grid
 */
require_once('db_connect.php');
include('includes/header.php');

// Fetch categories for the M3 Chips
$cat_query = mysqli_query($conn, "SELECT * FROM {$table_prefix}categories ORDER BY cat_name ASC");

// Fetch active products
$prod_query = mysqli_query($conn, "SELECT p.*, c.cat_name FROM {$table_prefix}products p 
                                   LEFT JOIN {$table_prefix}categories c ON p.cat_id = c.id 
                                   WHERE p.status = 'active' ORDER BY p.id DESC");
?>

<style>
    /* M3 Chip Styling */
    .m3-chip-container {
        display: flex;
        overflow-x: auto;
        padding-bottom: 12px;
        gap: 8px;
        scrollbar-width: none; /* Firefox */
    }
    .m3-chip-container::-webkit-scrollbar { display: none; } /* Chrome/Safari */

    .m3-chip {
        border: 1px solid var(--m3-outline);
        background: transparent;
        color: #49454f;
        border-radius: 8px;
        padding: 6px 16px;
        font-weight: 500;
        font-size: 14px;
        white-space: nowrap;
        transition: all 0.2s ease;
    }
    .m3-chip.active {
        background: #e8def8;
        border-color: #e8def8;
        color: #1d192b;
    }
    .m3-chip:hover:not(.active) {
        background: rgba(0, 95, 176, 0.05);
    }

    /* M3 Product Card Styling */
    .product-card {
        border: none;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        cursor: pointer;
        overflow: hidden;
    }
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    .product-card:active {
        transform: scale(0.97);
    }
    .card-img-container {
        width: 100%;
        height: 120px;
        background: #f1f3f4;
    }
    .card-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Mobile Bottom Bar visibility */
    @media (max-width: 991px) {
        .m3-bottom-bar { display: flex !important; }
    }
</style>

<div class="main-content">
    <div class="m3-chip-container mb-4">
        <button class="m3-chip active cat-filter" onclick="app_cart.filterCat('all', this)">
            All Items
        </button>
        <?php if($cat_query): while($cat = mysqli_fetch_assoc($cat_query)): ?>
            <button class="m3-chip cat-filter" onclick="app_cart.filterCat('<?php echo addslashes($cat['cat_name']); ?>', this)">
                <?php echo $cat['cat_name']; ?>
            </button>
        <?php endwhile; endif; ?>
    </div>

    <div class="row g-3" id="product-grid">
        <?php 
        if($prod_query && mysqli_num_rows($prod_query) > 0): 
            while($p = mysqli_fetch_assoc($prod_query)): 
                $img = !empty($p['image_url']) ? $p['image_url'] : 'assets/img/default-food.jpg'; 
        ?>
        <div class="col-xl-2 col-lg-3 col-md-4 col-6 prod-card-wrapper" 
             data-name="<?php echo strtolower($p['product_name']); ?>" 
             data-cat="<?php echo $p['cat_name']; ?>">
            
            <div class="card product-card h-100" onclick="app_cart.add('<?php echo addslashes($p['product_name']); ?>', <?php echo $p['price']; ?>, '<?php echo $img; ?>')">
                <div class="card-img-container">
                    <img src="<?php echo $img; ?>" alt="<?php echo $p['product_name']; ?>" loading="lazy">
                </div>
                <div class="card-body p-2 d-flex flex-column justify-content-between">
                    <h6 class="fw-bold small mb-1 text-dark text-truncate" title="<?php echo $p['product_name']; ?>">
                        <?php echo $p['product_name']; ?>
                    </h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary">₹<?php echo number_format($p['price'], 0); ?></span>
                        <span class="material-icons text-muted" style="font-size: 20px;">add_circle_outline</span>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            endwhile; 
        else: 
        ?>
            <div class="col-12 text-center py-5">
                <span class="material-icons fs-1 text-muted">search_off</span>
                <p class="text-muted">No products found. Please check back later.</p>
            </div>
        <?php endif; ?>
    </div>
</div>



<?php 
// Include Sidebar and Footer
include('includes/sidebar.php'); 
include('includes/footer.php'); 
?>