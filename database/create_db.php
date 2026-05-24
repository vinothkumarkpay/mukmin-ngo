<?php
try {
    $db = new PDO('mysql:host=127.0.0.1;port=3306', 'root', 'root');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec('CREATE DATABASE IF NOT EXISTS mukmin1 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    echo "Database 'mukmin1' created or already exists.\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
