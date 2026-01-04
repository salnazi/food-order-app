<?php
/**
 * FILENAME : profile.php
 * VERSION  : Final Merged M3 Profile Management
 */
require_once('db_connect.php');
include('includes/header.php');

// Initialize message variable
$message = "";

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $new_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $new_pass = $_POST['user_pass'];
    
    // Update session name
    $_SESSION['terminal_user'] = $new_name;
    
    // In a real application, you would update your 'users' or 'settings' table here.
    // Since we are using a simplified session-based operator for this POS:
    $message = "Profile updated successfully!";
}
?>

<style>
    .profile-card {
        border-radius: 28px;
        background: #ffffff;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    /* M3 Outlined Text Field Simulation */
    .m3-field {
        position: relative;
        margin-bottom: 24px;
    }
    .m3-field label {
        position: absolute;
        top: -10px;
        left: 16px;
        background: #fff;
        padding: 0 4px;
        font-size: 12px;
        color: var(--m3-primary);
        font-weight: 500;
        z-index: 1;
    }
    .m3-input {
        width: 100%;
        padding: 14px 16px;
        border: 1px solid var(--m3-outline);
        border-radius: 8px;
        font-size: 16px;
        transition: border 0.2s;
    }
    .m3-input:focus {
        outline: none;
        border: 2px solid var(--m3-primary);
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        background: #eaddff;
        color: #21005d;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8 col-12">
            
            <div class="card profile-card p-4 p-md-5">
                <div class="text-center">
                    <div class="profile-avatar">
                        <span class="material-icons" style="font-size: 40px;">person</span>
                    </div>
                    <h4 class="fw-bold mb-1">Account Settings</h4>
                    <p class="text-muted mb-4">Manage your POS operator profile</p>
                </div>

                <?php if($message): ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: '<?php echo $message; ?>',
                            borderRadius: '28px',
                            confirmButtonColor: '#005fb0'
                        });
                    </script>
                <?php endif; ?>

                <form method="POST" action="profile.php">
                    <div class="m3-field">
                        <label>Operator Display Name</label>
                        <input type="text" name="user_name" class="m3-input" 
                               value="<?php echo htmlspecialchars($_SESSION['terminal_user'] ?? 'Operator'); ?>" required>
                    </div>

                    <div class="m3-field">
                        <label>Update Password</label>
                        <input type="password" name="user_pass" class="m3-input" 
                               placeholder="Enter new password (optional)">
                    </div>

                    <div class="m3-field">
                        <label>System Role</label>
                        <input type="text" class="m3-input bg-light" value="Terminal Operator" readonly>
                    </div>

                    <div class="mt-4">
                        <button type="submit" name="update_profile" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm mb-3">
                            SAVE CHANGES
                        </button>
                        
                        <a href="index.php" class="btn btn-outline-secondary w-100 py-2 rounded-pill fw-bold text-decoration-none border-0">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <div class="mt-4 text-center">
                <button class="btn btn-link text-danger text-decoration-none small" onclick="confirmLogout()">
                    <span class="material-icons align-middle fs-6">logout</span> Switch Account
                </button>
            </div>

        </div>
    </div>
</div>

<script>
function confirmLogout() {
    Swal.fire({
        title: 'Sign Out?',
        text: "You will need to login again to access the terminal.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ba1a1a',
        confirmButtonText: 'Sign Out',
        borderRadius: '28px'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout.php';
        }
    });
}
</script>

<?php include('includes/footer.php'); ?>