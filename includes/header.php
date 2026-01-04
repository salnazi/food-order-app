<?php
/**
 * FILENAME : header.php
 * VERSION  : Final Merged M3 Header
 */
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once('db_connect.php');

// Fetch settings from database
$settings_query = mysqli_query($conn, "SELECT * FROM {$table_prefix}settings");
$set = [];
if($settings_query){
    while($row = mysqli_fetch_assoc($settings_query)){ 
        $set[$row['setting_key']] = $row['setting_value']; 
    }
}
$biz_name = $set['business_name'] ?? "JA SQUARE";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $biz_name; ?> | POS Terminal</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root { 
            --sidebar-w: 350px; 
            --nav-h: 70px; 
            --m3-surface: #f7f9fc; 
            --m3-primary: #005fb0; 
            --m3-on-primary: #ffffff;
            --m3-surface-container: #eeeeee;
            --m3-outline: #79747e;
        }

        body { 
            background: var(--m3-surface); 
            font-family: 'Roboto', sans-serif; 
            overflow-x: hidden; 
            color: #1c1b1f; 
        }

        /* Material 3 Top App Bar */
        .navbar-pos { 
            height: var(--nav-h); 
            background: #ffffff; 
            border-bottom: 1px solid #e0e0e0; 
            position: sticky; 
            top: 0; 
            z-index: 1060; 
        }

        .pos-wrapper { 
            display: flex; 
            width: 100%; 
            min-height: calc(100vh - var(--nav-h)); 
        }

        .main-content { 
            flex: 1; 
            padding: 20px; 
            margin-right: var(--sidebar-w); 
            padding-bottom: 100px; /* Padding for mobile bottom bar */
        }
        
        /* M3 Search Bar */
        .search-wrapper { 
            position: relative; 
            max-width: 450px; 
            width: 100%; 
            margin: 0 24px; 
        }
        .search-input { 
            height: 48px; 
            border-radius: 24px; 
            border: none; 
            background: var(--m3-surface-container); 
            padding-left: 52px; 
            font-size: 16px;
            transition: background 0.2s, box-shadow 0.2s;
        }
        .search-input:focus {
            background: #ffffff;
            box-shadow: 0 0 0 2px var(--m3-primary);
            outline: none;
        }
        .search-wrapper .material-icons { 
            position: absolute; 
            left: 18px; 
            top: 12px; 
            color: #49454f; 
        }

        /* Profile & Brand Styling */
        .navbar-brand {
            font-size: 20px;
            letter-spacing: -0.2px;
            color: #1c1b1f !important;
        }
        .profile-btn-container {
            width: 44px;
            height: 44px;
            background: #f1f3f4;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
        }
        .profile-btn-container:hover {
            background: #e8eaed;
        }

        /* Responsiveness */
        @media (max-width: 991px) {
            .main-content { margin-right: 0; padding: 16px; }
            .navbar-pos { height: auto; padding: 12px 16px; }
            .search-wrapper { display: none !important; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-pos px-3">
    <div class="container-fluid d-flex align-items-center justify-content-between p-0">
        <a class="navbar-brand fw-bold d-flex align-items-center mb-0" href="index.php">
            <span class="material-icons me-2" style="color:var(--m3-primary); font-size: 28px;">storefront</span> 
            <span><?php echo strtoupper($biz_name); ?></span>
        </a>

        <div class="search-wrapper d-none d-md-block">
            <span class="material-icons">search</span>
            <input type="text" id="pos-search" class="form-control search-input" placeholder="Search for items..." onkeyup="app_cart.search(this.value)">
        </div>

        <div class="dropdown">
            <div class="profile-btn-container shadow-sm" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="material-icons" style="color: #5f6368;">person_outline</span>
            </div>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 mt-2 p-2">
                <li class="px-3 py-2 border-bottom mb-2">
                    <p class="small text-muted mb-0">Current Session</p>
                    <p class="fw-bold mb-0 text-dark"><?php echo $_SESSION['terminal_user'] ?? 'Operator'; ?></p>
                </li>
                <li>
                    <a class="dropdown-item py-2 rounded-3 d-flex align-items-center" href="profile.php">
                        <span class="material-icons fs-5 me-2">settings</span> Account Settings
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-2 rounded-3 text-danger d-flex align-items-center" href="logout.php">
                        <span class="material-icons fs-5 me-2">logout</span> Sign out
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="pos-wrapper">