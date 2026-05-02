<?php
require_once 'config/database.php';
try {
    $stmt = $conn->query('DESCRIBE users');
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($cols as $col) {
        echo $col['Field'] . ' - ' . $col['Type'] . PHP_EOL;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
