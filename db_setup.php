<?php
/**
 * FILENAME : db_setup.php
 * PURPOSE  : Drop, Create, and Initialize M3 POS Tables with 30 Records
 */
require_once('db_connect.php');

// 1. Drop existing tables to ensure a clean state
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0;");
mysqli_query($conn, "DROP TABLE IF EXISTS `{$table_prefix}products`;");
mysqli_query($conn, "DROP TABLE IF EXISTS `{$table_prefix}categories`;");
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1;");

// 2. Create Categories Table
$create_cats = "CREATE TABLE `{$table_prefix}categories` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `cat_name` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 3. Create Products Table
$create_prods = "CREATE TABLE `{$table_prefix}products` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `cat_id` int(11) NOT NULL,
    `product_name` varchar(200) NOT NULL,
    `price` decimal(10,2) NOT NULL,
    `image_url` varchar(500) DEFAULT NULL,
    `status` enum('active','inactive') DEFAULT 'active',
    PRIMARY KEY (`id`),
    KEY `cat_id` (`cat_id`),
    CONSTRAINT `fk_category` FOREIGN KEY (`cat_id`) REFERENCES `{$table_prefix}categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

mysqli_query($conn, $create_cats);
mysqli_query($conn, $create_prods);

// 4. Insert Categories
$cats = ['Burgers', 'Pizza', 'Sides', 'Beverages', 'Desserts'];
foreach ($cats as $c) {
    mysqli_query($conn, "INSERT INTO `{$table_prefix}categories` (cat_name) VALUES ('$c')");
}

// 5. 30 Dummy Products
$products = [
    // Burgers (cat_id: 1)
    [1, 'Classic Beef Burger', 199, 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400'],
    [1, 'Cheese Blast Burger', 249, 'https://images.unsplash.com/photo-1550547660-d9450f859349?w=400'],
    [1, 'Crispy Chicken Burger', 179, 'https://images.unsplash.com/photo-1625813506062-0aeb1d7a094b?w=400'],
    [1, 'Veggie Supreme Burger', 159, 'https://images.unsplash.com/photo-1512152272829-e3139592d56f?w=400'],
    [1, 'Double Patty Monster', 349, 'https://images.unsplash.com/photo-1594212699903-ec8a3eca50f5?w=400'],
    [1, 'Spicy Paneer Burger', 189, 'https://images.unsplash.com/photo-1460306855393-0410f61241c7?w=400'],

    // Pizza (cat_id: 2)
    [2, 'Margherita Pizza', 299, 'https://images.unsplash.com/photo-1574071318508-1cdbad80ad38?w=400'],
    [2, 'Pepperoni Feast', 399, 'https://images.unsplash.com/photo-1628840042765-356cda07504e?w=400'],
    [2, 'Farmhouse Veggie', 349, 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=400'],
    [2, 'BBQ Chicken Pizza', 429, 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400'],
    [2, 'Paneer Tikka Pizza', 379, 'https://images.unsplash.com/photo-1594007654729-407eedc4be65?w=400'],
    [2, 'Four Cheese Pizza', 450, 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=400'],

    // Sides (cat_id: 3)
    [3, 'French Fries Large', 129, 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=400'],
    [3, 'Cheese Garlic Bread', 149, 'https://images.unsplash.com/photo-1573140247632-f8fd74997d5c?w=400'],
    [3, 'Chicken Nuggets (6pc)', 199, 'https://images.unsplash.com/photo-1562967914-608f82629710?w=400'],
    [3, 'Onion Rings', 119, 'https://images.unsplash.com/photo-1639024471283-03518883512d?w=400'],
    [3, 'Potato Wedges', 139, 'https://images.unsplash.com/photo-1623238914177-8898230988c1?w=400'],
    [3, 'Peri Peri Fries', 149, 'https://images.unsplash.com/photo-1630384060421-cb20d0e0649d?w=400'],

    // Beverages (cat_id: 4)
    [4, 'Cold Coffee', 99, 'https://images.unsplash.com/photo-1541167760496-1628856ab772?w=400'],
    [4, 'Iced Lemon Tea', 79, 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400'],
    [4, 'Strawberry Shake', 149, 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=400'],
    [4, 'Chocolate Frappe', 169, 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=400'],
    [4, 'Fresh Lime Soda', 69, 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=400'],
    [4, 'Mango Smoothie', 159, 'https://images.unsplash.com/photo-1523047974000-c0631665a39f?w=400'],

    // Desserts (cat_id: 5)
    [5, 'Chocolate Brownie', 129, 'https://images.unsplash.com/photo-1564355808539-22fda35bed7e?w=400'],
    [5, 'Red Velvet Cake', 189, 'https://images.unsplash.com/photo-1586788680434-30d324671ff6?w=400'],
    [5, 'Vanilla Ice Cream', 89, 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=400'],
    [5, 'Blueberry Cheesecake', 249, 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=400'],
    [5, 'Apple Pie', 199, 'https://images.unsplash.com/photo-1568571780765-9276ac8b75a2?w=400'],
    [5, 'Choco Lava Cake', 149, 'https://images.unsplash.com/photo-1624353365286-3f8d62daad51?w=400'],
];

foreach ($products as $p) {
    $stmt = mysqli_prepare($conn, "INSERT INTO `{$table_prefix}products` (cat_id, product_name, price, image_url) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "isds", $p[0], $p[1], $p[2], $p[3]);
    mysqli_stmt_execute($stmt);
}

echo "<h3>Database Cleaned & Setup Complete!</h3>";
echo "<p>All tables were dropped and recreated with 30 Material Design 3 compatible items.</p>";
echo "<a href='index.php'>Go to Terminal</a>";
?>