<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Application\Services\CartService;
use App\Infrastructure\Persistence\MySQLCartRepository;
use App\Infrastructure\HTTP\Controllers\CartController;

$config = require __DIR__ . '/../config/database.php';

try {
    // conexión PDO
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        $config['host'],
        $config['database'],
        $config['charset'] ?? 'utf8mb4' // Charset predeterminado
    );

    $pdo = new PDO($dsn, $config['user'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// repositorio y el servicio
$cartRepository = new MySQLCartRepository($pdo);
$cartService = new CartService($cartRepository);

// Cargar el carrito desde la base de datos
$cartService->loadCart();

$cartController = new CartController($cartService);

// rutas
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/cart/products' && $method === 'POST') {
    $cartController->addProduct();
} elseif (preg_match('/^\/cart\/products\/(\d+)$/', $uri, $matches) && $method === 'PUT') {
    $cartController->updateProductQuantity((int) $matches[1]);
} elseif (preg_match('/^\/cart\/products\/(\d+)$/', $uri, $matches) && $method === 'DELETE') {
    $cartController->removeProduct((int) $matches[1]);
} elseif ($uri === '/cart/products' && $method === 'GET') {
    $cartController->getProducts();
} elseif ($uri === '/cart/products/total' && $method === 'GET') {
    $cartController->getTotalProducts();
}
else if ($uri === '/cart/checkout' && $method === 'POST') {
    $cartController->confirmPurchase();
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found']);
}
