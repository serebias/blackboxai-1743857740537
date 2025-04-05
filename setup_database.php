<?php
require 'config.php';

try {
    initDatabase();
    echo "Database initialized successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>