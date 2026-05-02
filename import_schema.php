<?php
require_once 'config/database.php';
$sql = file_get_contents('database/schema.sql');
try {
    $conn->exec($sql);
    echo "Schema imported successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
