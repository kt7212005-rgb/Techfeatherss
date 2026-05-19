<?php
// generate_database.php
// Creates a fresh SQLite database with schema and sample seed data.

$dbFile = __DIR__ . '/data/poultry_new.db';
if (file_exists($dbFile)) {
    unlink($dbFile);
}
$db = new PDO('sqlite:' . $dbFile);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec('PRAGMA foreign_keys = ON');

$schema = file_get_contents(__DIR__ . '/database/schema.sql');
$db->exec($schema);

$passwordHash = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $db->prepare('INSERT INTO users (email, password, name, role) VALUES (?, ?, ?, ?)');
$stmt->execute(['admin@poultry.local', $passwordHash, 'Admin User', 'admin']);

$stmt = $db->prepare('INSERT INTO products (name, description, price, category, available_quantity) VALUES (?, ?, ?, ?, ?)');
$stmt->execute(['Fresh Eggs (Dozen)', 'Farm fresh eggs from healthy chickens', 120.00, 'eggs', 50]);
$stmt->execute(['Organic Feed (25kg)', 'Nutritious feed for poultry', 625.00, 'feed', 20]);
$stmt->execute(['Chicken Manure Fertilizer (50kg)', 'Natural fertilizer from our farm', 150.00, 'fertilizer', 30]);

echo "Created new database at: " . $dbFile . "\n";
?>