<?php
// config.php
// Database and application configuration

$secureSessionCookie = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
session_set_cookie_params(0, '/', '', $secureSessionCookie, true);
session_start();

// Database type: 'sqlite' or 'mysql'
define('DB_TYPE', 'mysql');

// SQLite configuration
define('DB_FILE', __DIR__ . '/data/poultry.db');

// MySQL configuration
define('DB_MYSQL_HOST', '127.0.0.1');
define('DB_MYSQL_NAME', 'poultry');
define('DB_MYSQL_USER', 'root');
define('DB_MYSQL_PASS', '');

define('BASE_URL', '/Techfeathers');

// Ensure data directory exists for SQLite support
if (!is_dir(__DIR__ . '/data')) {
    mkdir(__DIR__ . '/data', 0755, true);
}

// Create DB connection
function get_db() {
    static $db;
    if (!$db) {
        if (DB_TYPE === 'mysql') {
            $dsn = 'mysql:host=' . DB_MYSQL_HOST . ';dbname=' . DB_MYSQL_NAME . ';charset=utf8mb4';
            $db = new PDO($dsn, DB_MYSQL_USER, DB_MYSQL_PASS);
        } else {
            $db = new PDO('sqlite:' . DB_FILE);
        }
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $db;
}

// Initialize SQLite database only when using SQLite
if (DB_TYPE === 'sqlite' && !file_exists(DB_FILE)) {
    require_once __DIR__ . '/init_db.php';
}
