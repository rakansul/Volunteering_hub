<?php
// includes/db.php

// InfinityFree MySQL Credentials
$DB_HOST = 'sql306.infinityfree.com';
$DB_NAME = 'if0_39873353_test';
$DB_USER = 'if0_39873353';
$DB_PASS = 'vtkmBXnPZk';

// DSN (database connection string)
$dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    exit("Database connection failed: " . $e->getMessage());
}
