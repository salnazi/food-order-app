<?php
/**
 * Author : Salim Nazir
 * Email : salnazi@gmail.com
 * Powered By : JA Square
 * Module : JA Square Marketplace
 * FILENAME : order-status.php
 * Path : /food_order_app/order-status.php
 * Logic : Kitchen Display System (KDS) for tracking live POS orders.
 */
require_once('db_connect.php');
include('includes/header.php');

// Sample Order Data (In production, fetch from ja_square_restaurant_orders table)
$orders = [
    [
        'id' => 'JS-1024',
        'table' => 'T-04',
        'time' => '5 mins ago',
        'status' => 'Preparing',
        'items' => ['Italian Classic Pizza x 2', 'Coke Zero x 1'],
        'color' => 'warning'
    ],
    [
        'id' => 'JS-1025',
        'table' => 'Walk-in',
        'time' => '12 mins ago',
        'status' => 'Pending',
        'items' => ['Healthy Bowl Special x 1'],
        'color' => 'danger'
    ],
    [
        'id' => 'JS-1022',
        'table' => 'T-02',
        'time' => '25 mins ago',
        'status' => 'Ready',
        'items' => ['Gourmet Pasta x 1', 'Garlic Bread x 1'],
        'color' => 'success'
    ]
];
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Kitchen Display System</h3>
            <p class="text-muted small mb-0">Tracking 3 active orders across all terminals.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary rounded-pill btn-sm px-3" onclick="location.reload()">
                <span class="material-icons fs-6 align-middle">refresh</span> Refresh
            </button>
            <div class="btn-group">
                <button class="btn btn-light btn-sm border active">All</button>
                <button class="btn btn-light btn-sm border">Pending</button>
                <button class="btn btn-light btn-sm border">Ready</button>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <?php foreach($orders as $order): ?>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-white p-3 border-bottom d-flex justify-content-between align-items-start">
                    <div>
                        <span class="badge bg-<?php echo $order['color']; ?>-subtle text-<?php echo $order['color']; ?> fw-bold mb-1 px-2">
                            <?php echo strtoupper($order['status']); ?>
                        </span>
                        <h6 class="fw-bold mb-0"><?php echo $order['id']; ?></h6>
                        <small class="text-muted"><?php echo $order['table']; ?> • <?php echo $order['time']; ?></small>
                    </div>
                    <span class="material-icons text-muted opacity-50">more_vert</span>
                </div>

                <div class="card-body p-3">
                    <ul class="list-unstyled mb-0">
                        <?php foreach($order['items'] as $item): ?>
                        <li class="d-flex align-items-center mb-2">
                            <span class="material-icons text-primary fs-6 me-2">fiber_manual_record</span>
                            <span class="small fw-medium text-dark"><?php echo $item; ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="card-footer bg-light border-0 p-2">
                    <?php if($order['status'] == 'Pending'): ?>
                        <button class="btn btn-primary w-100 py-2 fw-bold small rounded-3" onclick="posNotify('Preparing Started')">START PREP</button>
                    <?php elseif($order['status'] == 'Preparing'): ?>
                        <button class="btn btn-success w-100 py-2 fw-bold small rounded-3" onclick="posNotify('Order is Ready!', 'success')">MARK AS READY</button>
                    <?php else: ?>
                        <button class="btn btn-secondary w-100 py-2 fw-bold small rounded-3" onclick="posNotify('Order Served')">SERVE/ARCHIVE</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-dashed d-flex align-items-center justify-content-center text-muted py-5" style="border: 2px dashed #cbd5e1; background: transparent;">
                <div class="text-center">
                    <span class="material-icons display-6 opacity-25">hourglass_empty</span>
                    <p class="small mt-2">Waiting for new orders...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-dashed {
        min-height: 250px;
    }
    .bg-warning-subtle { background-color: #fef9c3 !important; }
    .text-warning { color: #854d0e !important; }
    .bg-danger-subtle { background-color: #fee2e2 !important; }
    .text-danger { color: #991b1b !important; }
    .bg-success-subtle { background-color: #dcfce7 !important; }
    .text-success { color: #166534 !important; }
</style>

<?php 
include('includes/mobile_menu_nav.php'); 
include('includes/footer.php'); 
?>