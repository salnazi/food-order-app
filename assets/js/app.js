function addToCartFancy(name) {
    // POS Toast Notification
    Swal.fire({
        toast: true,
        position: 'top-start',
        title: 'Added: ' + name,
        background: '#2c3e50',
        color: '#fff',
        showConfirmButton: false,
        timer: 1000
    });
}