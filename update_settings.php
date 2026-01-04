<?php
/**
 * Author : Salim Nazir
 * Module : JA Square Marketplace
 * FILENAME : update_settings.php
 * Path : /food_order_app/update_settings.php
 * Logic : Securely updates system settings via AJAX from profile.php.
 */
require_once('db_connect.php');

// Set header for JSON response
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Sanitize inputs
    $tax_rate = mysqli_real_escape_string($conn, $_POST['tax_rate']);
    $wa_number = mysqli_real_escape_string($conn, $_POST['whatsapp_number']);
    $biz_name = mysqli_real_escape_string($conn, $_POST['business_name']);
    $currency = mysqli_real_escape_string($conn, $_POST['currency']);

    // Map of keys to update
    $updates = [
        'tax_rate' => $tax_rate,
        'whatsapp_number' => $wa_number,
        'business_name' => $biz_name,
        'currency' => $currency
    ];

    $success = true;

    foreach ($updates as $key => $value) {
        $sql = "UPDATE {$table_prefix}settings SET setting_value = '$value' WHERE setting_key = '$key'";
        if (!mysqli_query($conn, $sql)) {
            $success = false;
        }
    }

    if ($success) {
        echo json_encode(['status' => 'success', 'message' => 'Settings updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>