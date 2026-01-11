<?php
require_once 'config/config.php';

try {
    $result = $pdo->query("DESCRIBE users");
    $columns = $result->fetchAll();
    echo "Current users table structure:\n";
    foreach($columns as $col) {
        echo $col['Field'] . ' | ' . $col['Type'] . ' | ' . $col['Null'] . ' | ' . $col['Default'] . ' | ' . $col['Extra'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>