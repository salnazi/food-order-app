<?php
/**
 * Author : Salim Nazir
 * Email : salnazi@gmail.com
 * Powered By : JA Square
 * Module : JA Square Marketplace
 * FILENAME : mobile_menu_nav.php
 * Path : /food_order_app/includes/mobile_menu_nav.php
 * Logic : POS Quick-Action Dock for Mobile Terminals.
 */
?>
<style>
    /* POS Mobile Dock Styles */
    .pos-mobile-dock {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 70px;
        background: #ffffff;
        display: flex;
        justify-content: space-around;
        align-items: center;
        border-top: 2px solid #e2e8f0;
        z-index: 2000;
        box-shadow: 0 -4px 12px rgba(0,0,0,0.05);
        padding-bottom: env(safe-area-inset-bottom); /* Support for notched phones */
    }

    .dock-item {
        color: #64748b;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 20%;
        transition: all 0.2s ease;
        position: relative;
        border: none;
        background: none;
    }

    .dock-item.active {
        color: #3b82f6; /* POS Accent Blue */
    }

    .dock-item .material-icons {
        font-size: 26px;
        margin-bottom: 2px;
    }

    .dock-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Floating Action Button for the Ticket (Cart) */
    .dock-fab {
        background: #3b82f6 !important;
        color: white !important;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        margin-top: -35px;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        border: 4px solid #f8fafc !important;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .dock-fab .material-icons {
        margin-bottom: 0;
        font-size: 28px;
    }

    .badge-pos {
        position: absolute;
        top: 5px;
        right: 15px;
        background: #ef4444;
        color: white;
        font-size: 9px;
        padding: 2px 5px;
        border-radius: 10px;
        font-weight: bold;
        border: 2px solid white;
    }
    
    /* Active FAB override */
    .dock-fab.active {
        background: #2563eb !important;
    }
</style>

<div class="pos-mobile-dock d-lg-none">
    <a href="index.php" class="dock-item <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
        <span class="material-icons">grid_view</span>
        <span class="dock-label">Menu</span>
    </a>

    <a href="order-status.php" class="dock-item <?php echo (basename($_SERVER['PHP_SELF']) == 'order-status.php') ? 'active' : ''; ?>">
        <span class="material-icons">history</span>
        <span class="dock-label">Orders</span>
    </a>

    <button type="button" onclick="startCheckout()" class="dock-item dock-fab <?php echo (basename($_SERVER['PHP_SELF']) == 'cart.php') ? 'active' : ''; ?>">
        <span class="material-icons">receipt_long</span>
        <span class="badge-pos" id="mobile-cart-count">0</span>
    </button>

    <a href="menu.php" class="dock-item <?php echo (basename($_SERVER['PHP_SELF']) == 'menu.php') ? 'active' : ''; ?>">
        <span class="material-icons">manage_search</span>
        <span class="dock-label">Search</span>
    </a>

    <a href="profile.php" class="dock-item <?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : ''; ?>">
        <span class="material-icons">settings</span>
        <span class="dock-label">Setup</span>
    </a>
</div>

<div class="d-lg-none" style="height: 80px;"></div>

<script>
/**
 * Update the Mobile Badge Count on Load
 */
function updateMobileBadge() {
    const badge = document.getElementById('mobile-cart-count');
    if (badge) {
        // ticketItems is globally available from footer.php
        const count = ticketItems.reduce((total, item) => total + item.qty, 0);
        badge.innerText = count;
        // Hide badge if empty for cleaner look
        badge.style.display = count > 0 ? 'block' : 'none';
    }
}

// Intercept the global renderSidebar function to also update this mobile badge
const originalRenderSidebar = window.renderSidebar;
window.renderSidebar = function() {
    if (typeof originalRenderSidebar === 'function') originalRenderSidebar();
    updateMobileBadge();
};

document.addEventListener('DOMContentLoaded', updateMobileBadge);
</script>