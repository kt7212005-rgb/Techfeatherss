<?php
// add_new_tables.php
require_once __DIR__ . '/config.php';

$db = get_db();

// Add products table if not exists
$db->exec("CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    description TEXT,
    price REAL NOT NULL,
    category TEXT NOT NULL,
    available_quantity INTEGER NOT NULL DEFAULT 0
)");

// Add orders table if not exists
$db->exec("CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    quantity INTEGER NOT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status TEXT NOT NULL DEFAULT 'pending',
    FOREIGN KEY(user_id) REFERENCES users(id),
    FOREIGN KEY(product_id) REFERENCES products(id)
)");

// Seed products if empty
$products = $db->query("SELECT COUNT(*) as count FROM products")->fetch(PDO::FETCH_ASSOC);
if ($products['count'] == 0) {
    $stmt = $db->prepare('INSERT INTO products (name, description, price, category, available_quantity) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute(['Fresh Eggs (Dozen)', 'Farm fresh eggs from our healthy chickens', 120.00, 'eggs', 50]);
    $stmt->execute(['Organic Feed (25kg)', 'Nutritious feed for poultry', 625.00, 'feed', 20]);
    $stmt->execute(['Chicken Manure Fertilizer (50kg)', 'Natural fertilizer from our farm', 150.00, 'fertilizer', 30]);
}

echo "New tables and data added successfully.";
?>