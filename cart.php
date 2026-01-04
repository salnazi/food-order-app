<?php
/**
 * FILENAME : cart.php
 * VERSION  : Final Merged M3
 */
require_once('db_connect.php');
include('includes/header.php');
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-12">
            <div class="bg-white rounded-4 shadow-sm overflow-hidden mb-4 border-0">
                <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark">Review Order</span>
                    <span class="badge rounded-pill bg-primary px-3" id="item-count-badge">0 Items</span>
                </div>
                <div id="cart-review-content">
                    </div>
            </div>
            
            <div class="bg-white rounded-4 shadow-sm p-4 border-0">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span id="subtotal-val" class="fw-medium">₹0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">GST (1%)</span>
                    <span id="tax-val" class="fw-medium">₹0.00</span>
                </div>
                <hr class="text-muted opacity-25">
                <div class="d-flex justify-content-between mb-4">
                    <span class="h5 mb-0 fw-bold">Grand Total</span>
                    <h3 id="final-total-val" class="fw-bold text-primary mb-0">₹0.00</h3>
                </div>
                <button class="btn btn-primary w-100 py-3 rounded-pill fw-bold fs-5 shadow-sm mb-3" onclick="finish()">
                    PLACE ORDER
                </button>
                <a href="index.php" class="btn btn-outline-secondary w-100 py-2 rounded-pill fw-bold text-decoration-none border-0">
                    <span class="material-icons align-middle fs-6 me-1">arrow_back</span> Back to Menu
                </a>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script>
/**
 * Renders the Checkout list with M3 interactions
 * This is called automatically by app_cart.render() in footer.php
 */
function renderCartPage() {
    const container = document.getElementById('cart-review-content');
    const totalEl = document.getElementById('final-total-val');
    const subtotalEl = document.getElementById('subtotal-val');
    const taxEl = document.getElementById('tax-val');
    const badge = document.getElementById('item-count-badge');
    
    if(!container) return;

    let html = '';
    let total = 0;
    let count = 0;

    app_cart.items.forEach((item, index) => {
        let itemTotal = item.price * item.qty;
        total += itemTotal;
        count += item.qty;
        
        html += `
        <div class="d-flex justify-content-between p-3 border-bottom align-items-center">
            <div class="d-flex align-items-center overflow-hidden">
                <img src="${item.img}" class="rounded-3 me-3" width="64" height="64" style="object-fit:cover;">
                <div class="text-truncate">
                    <h6 class="mb-0 fw-bold text-dark text-truncate">${item.name}</h6>
                    <div class="d-flex align-items-center mt-2">
                        <button class="btn btn-outline-primary btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" 
                                style="width:28px; height:28px" 
                                onclick="app_cart.updateQty(${index}, -1)">
                            <span class="material-icons" style="font-size:18px">remove</span>
                        </button>
                        <span class="mx-3 fw-bold text-dark">${item.qty}</span>
                        <button class="btn btn-outline-primary btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" 
                                style="width:28px; height:28px" 
                                onclick="app_cart.updateQty(${index}, 1)">
                            <span class="material-icons" style="font-size:18px">add</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column align-items-end">
                <span class="fw-bold text-primary mb-1">₹${itemTotal.toFixed(2)}</span>
                <button class="btn btn-link text-danger p-0 border-0 text-decoration-none" onclick="app_cart.confirmDelete(${index})">
                    <span class="material-icons" style="font-size:20px">delete_outline</span>
                </button>
            </div>
        </div>`;
    });

    // Update UI Elements
    container.innerHTML = html || '<div class="p-5 text-center text-muted"><span class="material-icons fs-1 d-block mb-2">shopping_bag</span>Your cart is empty</div>';
    
    const tax = total * 0.01;
    const grandTotal = total + tax;

    if(subtotalEl) subtotalEl.innerText = '₹' + total.toLocaleString('en-IN', {minimumFractionDigits: 2});
    if(taxEl) taxEl.innerText = '₹' + tax.toLocaleString('en-IN', {minimumFractionDigits: 2});
    if(totalEl) totalEl.innerText = '₹' + grandTotal.toLocaleString('en-IN', {minimumFractionDigits: 2});
    if(badge) badge.innerText = count + " Items";
}

/**
 * Handles the Final Order Placement
 */
function finish() {
    if(app_cart.items.length === 0) {
        Swal.fire({
            title: 'Empty Cart',
            text: 'Add some items before placing an order.',
            icon: 'info',
            borderRadius: '28px',
            confirmButtonColor: '#005fb0'
        });
        return;
    }

    Swal.fire({
        title: 'Complete Order?',
        text: "Are you sure you want to finalize this transaction?",
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#005fb0',
        cancelButtonColor: '#79747e',
        confirmButtonText: 'Yes, Finalize',
        borderRadius: '28px'
    }).then((res) => {
        if(res.isConfirmed) {
            // Success Feedback
            Swal.fire({
                title: 'Order Successful',
                text: 'Order has been recorded successfully!',
                icon: 'success',
                confirmButtonColor: '#005fb0',
                borderRadius: '28px'
            }).then(() => {
                localStorage.removeItem('pos_cart');
                window.location.href = "index.php";
            });
        }
    });
}

// Ensure the page renders on load
document.addEventListener('DOMContentLoaded', renderCartPage);
</script>