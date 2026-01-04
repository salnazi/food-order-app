<?php
/**
 * FILENAME : dashboard.php
 * Logic : Main Public Facing POS Dashboard (No Admin Header).
 */
include('includes/public-header.php'); // Use the clean public header
?>

<div class="container-fluid px-0">
    <div class="row g-0">
        <main class="col-lg-8 col-xl-9 border-end bg-white" style="min-height: calc(100vh - 80px);">
            <div class="p-4">
                <div id="pos-menu-engine">
                    <?php include('menu.php'); ?>
                </div>
            </div>
        </main>

        <div class="col-lg-4 col-xl-3 bg-light">
            <?php include('includes/sidebar.php'); ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>