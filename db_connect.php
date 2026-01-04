<?php
/**
 * Author : Salim Nazir
 * Email : salnazi@gmail.com
 * Powered By : JA Square
 * Module : JA Square Marketplace
 * FILENAME : db_connect.php
 * Path : /food_order_app/db_connect.php
 * Updated: 2025-12-30 00:20:00 (Asia/Kolkata +5:30)
 * Logic : Centralized Database Connection with Environment Detection.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host_name = $_SERVER['HTTP_HOST'];
$is_live = (strpos($host_name, 'jafmarketplace.in') !== false);

if ($is_live) {
    $host = 'localhost';
    $user = 'jafmarke_app'; 
    $pass = 'Splender@84';           
    $dbname = 'jafmarke_app'; 
    $tbl_prefix = 'foodorder_';
} else {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'jafmarke_app';
    $tbl_prefix = 'foodorder_';
}

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

// Global table prefix variable for queries
$table_prefix = $tbl_prefix;
?>