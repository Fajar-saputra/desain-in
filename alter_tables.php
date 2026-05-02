<?php
// This script adds missing columns to existing tables
require_once 'config/database.php';

$alters = [
    "ALTER TABLE users ADD COLUMN username VARCHAR(80) NOT NULL UNIQUE AFTER id",
    "ALTER TABLE users ADD COLUMN phone VARCHAR(30) NOT NULL AFTER username",
    "ALTER TABLE users MODIFY COLUMN role ENUM('user','designer','admin') NOT NULL DEFAULT 'user'",
    "ALTER TABLE services ADD COLUMN image_url VARCHAR(255) AFTER price_print",
    "ALTER TABLE services ADD COLUMN designer_id INT NULL AFTER image_url",
    "ALTER TABLE services ADD CONSTRAINT fk_services_designer FOREIGN KEY (designer_id) REFERENCES users(id) ON DELETE SET NULL",
];

foreach ($alters as $sql) {
    try {
        $conn->exec($sql);
        echo "Executed: $sql\n";
    } catch (PDOException $e) {
        echo "Error on $sql: " . $e->getMessage() . "\n";
    }
}

echo "Alterations completed.\n";
