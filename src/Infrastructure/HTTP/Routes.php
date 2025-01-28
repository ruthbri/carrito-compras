<?php

use App\Infrastructure\HTTP\Controllers\CartController;
use App\Application\Services\CartService;
use App\Infrastructure\Persistence\MySQLCartRepository;

require __DIR__ . '/../src/bootstrap.php';

$pdo = require __DIR__ . '/../src/bootstrap.php';

// repositorio y el servicio
$cartRepository = new MySQLCartRepository($pdo);
$cartService = new CartService($cartRepository);

// controlador del carrito
$cartController = new CartController($cartService);

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// rutas RESTful
if ($uri === '/cart/products' && $method === 'POST') {

    // Agrega producto al carrito
    $cartController->addProduct();

} elseif (preg_match('/^\/cart\/products\/(\d+)$/', $uri, $matches) && $method === 'PUT') {

    // Actualiza cantidad de un producto
    $cartController->updateProductQuantity((int) $matches[1]);

} elseif (preg_match('/^\/cart\/products\/(\d+)$/', $uri, $matches) && $method === 'DELETE') {

    // Elimina un producto del carrito
    $cartController->removeProduct((int) $matches[1]);

} elseif ($uri === '/cart/products' && $method === 'GET') {

    // Obtene todos los productos del carrito
    $cartController->getProducts();

} elseif ($uri === '/cart/products/total' && $method === 'GET') {

    // Obtene el número total de productos en el carrito
    $cartController->getTotalProducts();

} else {

    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found']);
}
