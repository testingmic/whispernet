<?php
global $databases, $alterTables;

use CodeIgniter\Database\Exceptions\DatabaseException;

// Create the databases
$databases = [
    "CREATE TABLE IF NOT EXISTS `currencies` (
        `id` INTEGER PRIMARY KEY AUTOINCREMENT,
        `name` TEXT NOT NULL,
        `code` TEXT NOT NULL,
        `symbol` TEXT NOT NULL,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    CREATE INDEX IF NOT EXISTS `idx_currencies_name` ON `currencies` (`name`);
    CREATE INDEX IF NOT EXISTS `idx_currencies_code` ON `currencies` (`code`);",
    "CREATE TABLE IF NOT EXISTS `rates` (
        `id` INTEGER PRIMARY KEY AUTOINCREMENT,
        `company_id` INTEGER NOT NULL,
        `currency_id` INTEGER NOT NULL,
        `currency_code` TEXT NOT NULL,
        `currency_symbol` TEXT NOT NULL,
        `rate` DECIMAL(10, 4) NOT NULL,
        `base_rates` TEXT NOT NULL,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    CREATE INDEX IF NOT EXISTS `idx_rates_company_id` ON `rates` (`company_id`);
    CREATE INDEX IF NOT EXISTS `idx_rates_currency_id` ON `rates` (`currency_id`);
    CREATE INDEX IF NOT EXISTS `idx_rates_currency_code` ON `rates` (`currency_code`);",
    "CREATE TABLE IF NOT EXISTS `companies` (
        `id` INTEGER PRIMARY KEY AUTOINCREMENT,
        `name` TEXT NOT NULL,
        `image` TEXT NOT NULL,
        `category` TEXT NOT NULL,
        `url` TEXT NOT NULL
    );
    CREATE INDEX IF NOT EXISTS `idx_companies_name` ON `companies` (`name`);
    CREATE INDEX IF NOT EXISTS `idx_companies_category` ON `companies` (`category`);",
];

// alter tables
$alterTables = [
    // "ALTER TABLE funnels ADD COLUMN created_at DATETIME;"
    // "ALTER TABLE funnels ADD COLUMN updated_at DATETIME;"
];

function createDatabaseStructure() {
    global $databases, $alterTables;
    $db = \Config\Database::connect();
    foreach(array_merge($databases, $alterTables) as $query) {
        try {
            $db->query($query);
        } catch(DatabaseException $e) { }
    }
}

/**
 * Set the database settings
 * 
 * @param object $dbHandler
 * 
 * @return void
 */
function setDatabaseSettings($dbHandler) {
    $dbHandler->query("PRAGMA journal_mode = WAL");
    $dbHandler->query("PRAGMA synchronous = NORMAL");
    $dbHandler->query("PRAGMA locking_mode = NORMAL");
    $dbHandler->query("PRAGMA busy_timeout = 5000");
    $dbHandler->query("PRAGMA cache_size = -16000");
}
?>