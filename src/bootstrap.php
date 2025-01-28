<?php

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/database.php';

try {
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        $config['host'],
        $config['database'],
        $config['charset'] ?? 'utf8mb4'
    );

    $pdo = new PDO(
        $dsn,
        $config['user'],
        $config['password']
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $pdo;
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

