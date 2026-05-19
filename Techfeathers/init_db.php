<?php
// init_db.php
// Creates the SQLite database and seed data.

$db = new PDO('sqlite:' . DB_FILE);
db_init($db);

function db_init(PDO $db) {
    $db->exec('PRAGMA foreign_keys = ON');

    // Users table
    $db->exec("CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        name TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT 'manager',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Batches / chickens
    $db->exec("CREATE TABLE batches (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        batch_code TEXT NOT NULL UNIQUE,
        breed TEXT NOT NULL,
        started_at DATE NOT NULL,
        quantity INTEGER NOT NULL DEFAULT 0,
        status TEXT NOT NULL DEFAULT 'active'
    )");

    // Egg production entries
    $db->exec("CREATE TABLE eggs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        batch_id INTEGER NOT NULL,
        collected_at DATE NOT NULL,
        quantity INTEGER NOT NULL DEFAULT 0,
        FOREIGN KEY(batch_id) REFERENCES batches(id) ON DELETE CASCADE
    )");

    // Feed inventory
    $db->exec("CREATE TABLE feed_inventory (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        quantity_kg REAL NOT NULL DEFAULT 0,
        unit_cost REAL NOT NULL DEFAULT 0,
        last_updated DATE NOT NULL
    )");

    // Financial transactions
    $db->exec("CREATE TABLE finances (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        type TEXT NOT NULL CHECK(type IN ('sale','expense')),
        description TEXT NOT NULL,
        amount REAL NOT NULL,
        incurred_at DATE NOT NULL
    )");

    // Products for ordering
    $db->exec("CREATE TABLE products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        description TEXT,
        price REAL NOT NULL,
        category TEXT NOT NULL,
        available_quantity INTEGER NOT NULL DEFAULT 0
    )");

    // Customer orders
    $db->exec("CREATE TABLE orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        product_id INTEGER NOT NULL,
        quantity INTEGER NOT NULL,
        order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        status TEXT NOT NULL DEFAULT 'pending',
        FOREIGN KEY(user_id) REFERENCES users(id),
        FOREIGN KEY(product_id) REFERENCES products(id)
    )");

    // Seed admin user (password: admin123)
    $passwordHash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $db->prepare('INSERT INTO users (email, password, name, role) VALUES (?, ?, ?, ?)');
    $stmt->execute(['admin@poultry.local', $passwordHash, 'Admin User', 'admin']);

    // Seed example batch data
    $stmt = $db->prepare('INSERT INTO batches (batch_code, breed, started_at, quantity, status) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute(['B-201', 'Leghorn', date('Y-m-d', strtotime('-7 days')), 120, 'active']);
    $stmt->execute(['B-202', 'Rhode Island Red', date('Y-m-d', strtotime('-10 days')), 90, 'active']);

    // Seed egg production for the last 7 days
    $today = new DateTime();
    for ($i = 6; $i >= 0; $i--) {
        $day = clone $today;
        $day->modify("-{$i} days");
        $date = $day->format('Y-m-d');
        $qty = rand(30, 90);
        $stmt = $db->prepare('INSERT INTO eggs (batch_id, collected_at, quantity) VALUES (?, ?, ?)');
        $stmt->execute([1, $date, $qty]);
        $stmt->execute([2, $date, (int)($qty * 0.9)]);
    }

    // Seed feed inventory
    $stmt = $db->prepare('INSERT INTO feed_inventory (name, quantity_kg, unit_cost, last_updated) VALUES (?, ?, ?, ?)');
    $stmt->execute(['Layer Mash', 250.0, 25.0, date('Y-m-d')]);
    $stmt->execute(['Grower Feed', 150.0, 22.5, date('Y-m-d')]);

    // Mortality records
    $db->exec("CREATE TABLE mortality_records (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        batch_id INTEGER NOT NULL,
        recorded_at DATE NOT NULL,
        deaths INTEGER NOT NULL DEFAULT 0,
        reason TEXT,
        notes TEXT,
        FOREIGN KEY(batch_id) REFERENCES batches(id) ON DELETE CASCADE
    )");

    // Seed finances
    $stmt = $db->prepare('INSERT INTO finances (type, description, amount, incurred_at) VALUES (?, ?, ?, ?)');
    $stmt->execute(['sale', 'Egg sales', 420.00, date('Y-m-d', strtotime('-3 days'))]);
    $stmt->execute(['expense', 'Feed purchase', 190.00, date('Y-m-d', strtotime('-2 days'))]);

    // Seed products
    $stmt = $db->prepare('INSERT INTO products (name, description, price, category, available_quantity) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute(['Fresh Eggs (Dozen)', 'Farm fresh eggs from our healthy chickens', 120.00, 'eggs', 50]);
    $stmt->execute(['Organic Feed (25kg)', 'Nutritious feed for poultry', 625.00, 'feed', 20]);
    $stmt->execute(['Chicken Manure Fertilizer (50kg)', 'Natural fertilizer from our farm', 150.00, 'fertilizer', 30]);
}
