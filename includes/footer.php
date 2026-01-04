<?php
/**
 * FILENAME : footer.php
 * VERSION  : Final Merged M3 Cart Engine
 */
?>
</div> <?php if(basename($_SERVER['PHP_SELF']) == 'index.php'): ?>
<div class="m3-bottom-bar d-lg-none" style="position: fixed; bottom: 0; left: 0; right: 0; height: 80px; background: #fff; border-top: 1px solid #e0e0e0; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; z-index: 1070; box-shadow: 0 -2px 10px rgba(0,0,0,0.05);">
    <div>
        <small class="text-muted d-block" style="font-size: 12px;">Total (incl. tax)</small>
        <h5 class="fw-bold mb-0" id="mobile-total-display" style="color: var(--m3-primary);">₹0.00</h5>
    </div>
    <button class="btn btn-primary rounded-pill px-4 py-2 fw-bold d-flex align-items-center" onclick="app_cart.toggleMobileSidebar()">
        <span class="material-icons me-2">shopping_bag</span> View Cart
    </button>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const app_cart = {
    // Load items from LocalStorage
    items: JSON.parse(localStorage.getItem('pos_cart')) || [],
    
    // Save to LocalStorage and trigger UI refresh
    save: function() { 
        localStorage.setItem('pos_cart', JSON.stringify(this.items)); 
        this.render(); 
    },
    
    // Add product to cart
    add: function(name, price, img) {
        const found = this.items.find(i => i.name === name);
        if (found) { 
            found.qty++; 
        } else { 
            this.items.push({ name, price: parseFloat(price), img, qty: 1 }); 
        }
        this.save();
        
        // M3 Snackbar / Toast feedback
        Swal.fire({
            toast: true, position: 'top-end', icon: 'success',
            title: 'Added to cart', showConfirmButton: false, timer: 1000,
            background: '#fefefe'
        });
    },
    
    // Core Quantity Adjustment (+ / -)
    updateQty: function(index, delta) {
        if (this.items[index]) {
            this.items[index].qty += delta;
            if (this.items[index].qty <= 0) {
                this.confirmDelete(index); // Trigger Modal if qty hits zero
            } else {
                this.save();
            }
        }
    },

    // Unified Material 3 Confirmation Modal
    confirmDelete: function(index) {
        const item = this.items[index];
        Swal.fire({
            title: 'Remove item?',
            text: `Do you want to remove "${item.name}" from your order?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ba1a1a', // M3 Error Color
            cancelButtonColor: '#79747e',
            confirmButtonText: 'Yes, Remove',
            borderRadius: '28px',
            backdrop: `rgba(0,0,0,0.4)` // Ensures focus on modal
        }).then((result) => {
            if (result.isConfirmed) {
                this.items.splice(index, 1);
                this.save();
                
                // If on cart review page and no items left, redirect home
                if(window.location.pathname.includes('cart.php') && this.items.length === 0) {
                    window.location.href = 'index.php';
                }
            } else if (this.items[index] && this.items[index].qty <= 0) {
                // Reset to 1 if user cancels deletion
                this.items[index].qty = 1;
                this.save();
            }
        });
    },
    
    // Clear entire cart
    clear: function() { 
        if(this.items.length === 0) return;
        Swal.fire({
            title: 'Clear Ticket?',
            text: "This will remove all items from your current order.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ba1a1a',
            cancelButtonColor: '#79747e',
            confirmButtonText: 'Clear All',
            borderRadius: '28px'
        }).then((res) => { 
            if (res.isConfirmed) { 
                this.items = []; 
                this.save(); 
            }
        });
    },
    
    // Main Render Loop
    render: function() {
        const isCartPage = window.location.pathname.includes('cart.php');
        const container = document.getElementById('sidebar-list');
        const sideTotal = document.getElementById('side-total');
        const mobileTotal = document.getElementById('mobile-total-display');
        
        let total = 0;
        let html = '';

        this.items.forEach((item, idx) => {
            total += (item.price * item.qty);
            
            // Build Sidebar HTML only if NOT on cart.php to prevent mobile UI jumping
            if (!isCartPage) {
                html += `
                <div class="d-flex align-items-center mb-3 p-3 bg-light rounded-4 border-0">
                    <div class="flex-grow-1 overflow-hidden">
                        <span class="fw-bold d-block mb-1 text-truncate">${item.name}</span>
                        <small class="text-muted">₹${item.price} x ${item.qty}</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-sm btn-white border rounded-circle p-1 d-flex" onclick="app_cart.updateQty(${idx}, -1)">
                            <span class="material-icons" style="font-size:16px">remove</span>
                        </button>
                        <span class="fw-bold mx-1">${item.qty}</span>
                        <button class="btn btn-sm btn-white border rounded-circle p-1 d-flex" onclick="app_cart.updateQty(${idx}, 1)">
                            <span class="material-icons" style="font-size:16px">add</span>
                        </button>
                    </div>
                </div>`;
            }
        });

        // Update Sidebars/Totals
        if (container && !isCartPage) {
            container.innerHTML = html || '<div class="text-center py-5 opacity-50"><span class="material-icons fs-1">shopping_basket</span><p>Empty Ticket</p></div>';
        }
        
        const formattedTotal = '₹' + (total * 1.01).toLocaleString('en-IN', {minimumFractionDigits: 2});
        if(sideTotal) sideTotal.innerText = formattedTotal;
        if(mobileTotal) mobileTotal.innerText = formattedTotal;
        
        // Sync with cart.php if applicable
        if (isCartPage && typeof renderCartPage === 'function') {
            renderCartPage();
        }
    },
    
    // Search Functionality
    search: function(val) {
        const query = val.toLowerCase();
        document.querySelectorAll('.prod-card-wrapper').forEach(card => {
            const match = card.getAttribute('data-name').includes(query);
            card.style.display = match ? 'block' : 'none';
        });
    },
    
    // Category Filtering
    filterCat: function(cat, btn) {
        document.querySelectorAll('.cat-filter').forEach(b => b.classList.remove('active', 'btn-primary'));
        btn.classList.add('active', 'btn-primary');
        document.querySelectorAll('.prod-card-wrapper').forEach(card => {
            const match = (cat === 'all' || card.getAttribute('data-cat') === cat);
            card.style.display = match ? 'block' : 'none';
        });
    },
    
    // Process Payment Redirect
    go: function() { 
        if (this.items.length === 0) {
            Swal.fire({ icon: 'info', title: 'Cart Empty', text: 'Add items before checkout', borderRadius: '28px' });
            return;
        } 
        window.location.href = "cart.php"; 
    },

    // Mobile Sidebar Toggle
    toggleMobileSidebar: function() { 
        const sidebar = document.getElementById('main-sidebar');
        if(sidebar) sidebar.classList.toggle('active'); 
    }
};

// Initialize App
document.addEventListener('DOMContentLoaded', () => app_cart.render());
</script>
</body>
</html>