<?php

namespace App\Infrastructure\HTTP\Controllers;

use App\Application\Services\CartService;
use App\Application\DTO\CartItemDTO;
use App\Domain\Exceptions\CartException;

class CartController
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * POST /cart/products
     * Agrega un producto al carrito.
     */
    public function addProduct(): void
    {
        $decodedData = $this->getInputData();

        if (!is_array($decodedData) || empty($decodedData)) {
            $this->sendResponse(['error' => 'Malformed JSON'], 400);
            return;
        }

        try {

            $cartItemDTO = CartItemDTO::fromArray($decodedData);
            $this->cartService->addProduct($cartItemDTO);
            $this->sendResponse(['message' => 'Product added to cart'], 201);

        } catch (CartException $e) {

            $this->sendResponse(['error' => $e->getMessage()], 400);

        } catch (\Exception $e) {

            $this->sendResponse(['error' => 'Unexpected error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * PUT /cart/products/{product_id}
     * Actualiza la cantidad de un producto en el carrito.
     */
    public function updateProductQuantity(int $productId): void
    {
        $decodedData = $this->getInputData();

        if ($decodedData === null || !isset($decodedData['quantity'])) {
            $this->sendResponse(['error' => 'Invalid input'], 400);
            return;
        }

        try {

            $quantity = (int) $decodedData['quantity'];
            $this->cartService->updateProductQuantity($productId, $quantity);
            $this->sendResponse(['message' => 'Product quantity updated'], 200);

        } catch (CartException $e) {

            $this->sendResponse(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * DELETE /cart/products/{product_id}
     * Elimina un producto del carrito.
     */
    public function removeProduct(int $productId): void
    {
        try {

            $this->cartService->removeProduct($productId);
            $this->sendResponse(['message' => 'Product removed from cart'], 200);

        } catch (CartException $e) {

            $this->sendResponse(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * GET /cart/products
     * Obtiene todos los productos del carrito.
     */
    public function getProducts(): void
    {
        $products = $this->cartService->getProducts();

        $productsArray = array_map(function ($item) {
            return [
                'product_id' => $item->getProductId(),
                'quantity'   => $item->getQuantity(),
                'price'      => $item->getPrice(),
                'total_price'=> $item->calculateTotalPrice(),
            ];
        }, $products);

        $this->sendResponse(['products' => $productsArray], 200);
    }

    /**
     * GET /cart/products/total
     * Obtiene el número total de productos en el carrito.
     */
    public function getTotalProducts(): void
    {
        try {

            $totalProducts = $this->cartService->getTotalProducts();
            $this->sendResponse(['total_products' => $totalProducts], 200);

        } catch (\Exception $e) {

            $this->sendResponse(['error' => 'Unexpected error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Obtiene y decodifica los datos JSON de entrada.
     *
     * @return array<string, mixed>|null
     */
    private function getInputData(): ?array
    {
        $data = file_get_contents('php://input');
        if ($data === false || empty($data)) {
            return null;
        }

        $decodedData = json_decode($data, true);
        if (!is_array($decodedData)) {
            return null;
        }

        return $decodedData;
    }


    /**
     * @param array<string, mixed> $data
     */
    private function sendResponse(array $data, int $statusCode): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function confirmPurchase(): void
    {
        try {

            $this->cartService->confirmPurchase();
            $this->sendResponse(['message' => 'Purchase confirmed'], 200);

        } catch (\Exception $e) {

            $this->sendResponse(['error' => $e->getMessage()], 500);
        }
    }
}
