<?php
/**
 * FILENAME : checkout.php
 * VERSION  : Final Merged M3 Payment Selection
 */
require_once('db_connect.php');
include('includes/header.php');
?>

<style>
    .payment-card {
        border: 2px solid #e0e0e0;
        border-radius: 20px;
        transition: all 0.2s ease;
        cursor: pointer;
        background: #fff;
    }
    .payment-card:hover {
        border-color: var(--m3-primary);
        background: rgba(0, 95, 176, 0.04);
        transform: translateY(-2px);
    }
    .payment-card.selected {
        border-color: var(--m3-primary);
        background: #e8def8;
    }
    .payment-icon {
        font-size: 40px;
        color: var(--m3-primary);
        margin-bottom: 12px;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-12">
            <div class="text-center mb-4">
                <h3 class="fw-bold">Payment Method</h3>
                <p class="text-muted">Finalize your order by selecting a payment type</p>
            </div>

            <div class="bg-primary text-white rounded-4 p-4 text-center mb-4 shadow">
                <span class="opacity-75 d-block small mb-1">TOTAL AMOUNT DUE</span>
                <h1 class="fw-bold mb-0" id="checkout-grand-total">₹0.00</h1>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-4">
                    <div class="payment-card p-3 text-center h-100" onclick="selectPayment('Cash', this)">
                        <span class="material-icons payment-icon">payments</span>
                        <div class="fw-bold small">CASH</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="payment-card p-3 text-center h-100" onclick="selectPayment('Card', this)">
                        <span class="material-icons payment-icon">credit_card</span>
                        <div class="fw-bold small">CARD</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="payment-card p-3 text-center h-100" onclick="selectPayment('UPI', this)">
                        <span class="material-icons payment-icon">qr_code_2</span>
                        <div class="fw-bold small">UPI / QR</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-4 p-4 border shadow-sm">
                <div class="d-flex justify-content-between mb-3 text-muted">
                    <span>Items Count:</span>
                    <span id="checkout-item-count" class="fw-bold">0</span>
                </div>
                <button class="btn btn-primary w-100 py-3 rounded-pill fw-bold fs-5 shadow-sm mb-3" onclick="processOrder()">
                    FINALIZE TRANSACTION
                </button>
                <a href="cart.php" class="btn btn-outline-secondary w-100 py-2 rounded-pill fw-bold text-decoration-none border-0">
                    Back to Review
                </a>
            </div>
        </div>
    </div>
</div>



<?php include('includes/footer.php'); ?>

<script>
let selectedMethod = null;

function selectPayment(method, element) {
    // Remove selected class from all cards
    document.querySelectorAll('.payment-card').forEach(card => card.classList.remove('selected'));
    // Add to clicked card
    element.classList.add('selected');
    selectedMethod = method;
}

function updateCheckoutUI() {
    const totalEl = document.getElementById('checkout-grand-total');
    const countEl = document.getElementById('checkout-item-count');
    
    let total = 0;
    let count = 0;
    
    app_cart.items.forEach(item => {
        total += (item.price * item.qty);
        count += item.qty;
    });

    const grandTotal = total * 1.01; // Including the 1% tax logic
    totalEl.innerText = '₹' + grandTotal.toLocaleString('en-IN', {minimumFractionDigits: 2});
    countEl.innerText = count;

    // If somehow cart is empty, go back
    if(count === 0) window.location.href = 'index.php';
}

function processOrder() {
    if(!selectedMethod) {
        Swal.fire({
            title: 'Payment Method?',
            text: 'Please select Cash, Card, or UPI to continue.',
            icon: 'warning',
            borderRadius: '28px',
            confirmButtonColor: '#005fb0'
        });
        return;
    }

    Swal.fire({
        title: 'Confirm Transaction',
        text: `Complete order via ${selectedMethod}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#005fb0',
        confirmButtonText: 'Yes, Finish',
        borderRadius: '28px'
    }).then((res) => {
        if(res.isConfirmed) {
            // Here you would normally send an AJAX request to save order in DB
            Swal.fire({
                title: 'Order Completed!',
                text: 'Transaction was successful.',
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

// Initial Call
document.addEventListener('DOMContentLoaded', updateCheckoutUI);
</script>