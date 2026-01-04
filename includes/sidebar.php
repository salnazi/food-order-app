<?php
/**
 * FILENAME : sidebar.php
 * VERSION  : Final Merged M3 Sidebar
 */
?>
<style>
    /* M3 Sidebar Container */
    .sidebar-pos {
        width: var(--sidebar-w);
        height: calc(100vh - var(--nav-h));
        background: #ffffff;
        border-left: 1px solid #e0e0e0;
        position: fixed;
        right: 0;
        top: var(--nav-h);
        display: flex;
        flex-direction: column;
        z-index: 1050;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Scrollable Ticket Area */
    .sidebar-body {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        scrollbar-width: thin;
    }

    /* Bottom Action Area */
    .sidebar-footer {
        padding: 20px;
        background: #ffffff;
        border-top: 1px solid #f1f3f4;
    }

    /* M3 Mobile Sidebar Overlay Logic */
    @media (max-width: 991px) {
        .sidebar-pos {
            position: fixed;
            right: 0;
            top: 0;
            height: 100vh;
            width: 100%;
            max-width: 400px;
            transform: translateX(100%);
            box-shadow: -5px 0 25px rgba(0,0,0,0.1);
            z-index: 2000;
        }
        .sidebar-pos.active {
            transform: translateX(0);
        }
        .mobile-sidebar-close {
            display: block !important;
        }
    }

    .btn-white {
        background: #ffffff;
        color: #49454f;
    }
</style>

<aside class="sidebar-pos" id="main-sidebar">
    <div class="p-3 d-flex align-items-center justify-content-between border-bottom mobile-sidebar-close" style="display:none;">
        <span class="fw-bold fs-5">Current Order</span>
        <button class="btn btn-light rounded-circle p-2 d-flex" onclick="app_cart.toggleMobileSidebar()">
            <span class="material-icons">close</span>
        </button>
    </div>

    <div class="px-4 py-3 d-none d-lg-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-dark">Current Ticket</h5>
        <button class="btn btn-sm btn-outline-danger border-0 rounded-pill px-3" onclick="app_cart.clear()">
            <span class="material-icons align-middle fs-6">delete_sweep</span> Clear
        </button>
    </div>

    <div class="sidebar-body" id="sidebar-list">
        <div class="text-center py-5 opacity-50">
            <span class="material-icons fs-1">shopping_basket</span>
            <p>Empty Ticket</p>
        </div>
    </div>

    <div class="sidebar-footer">
        <div class="d-flex justify-content-between mb-2">
            <span class="text-muted small">Subtotal</span>
            <span class="fw-bold small" id="side-subtotal">₹0.00</span>
        </div>
        <div class="d-flex justify-content-between mb-3">
            <span class="text-muted small">Tax (GST 1%)</span>
            <span class="fw-bold small" id="side-tax">₹0.00</span>
        </div>
        
        <div class="bg-primary rounded-4 p-3 text-white d-flex justify-content-between align-items-center mb-3 shadow-sm">
            <div>
                <small class="opacity-75 d-block" style="font-size: 11px;">TO PAY</small>
                <h4 class="fw-bold mb-0" id="side-total">₹0.00</h4>
            </div>
            <span class="material-icons fs-1 opacity-25">payments</span>
        </div>

        <button class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center" onclick="app_cart.go()">
            PROCEED TO CHECKOUT
            <span class="material-icons ms-2">arrow_forward</span>
        </button>
    </div>
</aside>

<script>
/**
 * Note: The actual rendering logic is handled by the global app_cart object 
 * in footer.php to ensure synchronization across index.php and cart.php.
 */
</script>