<?php

namespace App\Application\Services;

use App\Application\DTO\CartItemDTO;
use App\Domain\Cart\Cart;
use App\Domain\Cart\CartItem;
use App\Domain\Cart\CartRepository;
use App\Domain\Exceptions\CartException;

class CartService
{
    private CartRepository $repository;
    private Cart $cart;

    public function __construct(CartRepository $repository)
    {
        $this->repository = $repository;

        $this->cart = $this->repository->load();
    }

    /**
     * Agrega un producto al carrito.
     */
    public function addProduct(CartItemDTO $cartItemDTO): void
    {
        $cartItem = new CartItem(
            $cartItemDTO->getProductId(),
            $cartItemDTO->getQuantity(),
            $cartItemDTO->getPrice()
        );

        $this->cart->addItem($cartItem);

        $this->repository->save($this->cart);
    }

    /**
     * Actualiza la cantidad de un producto en el carrito.
     */
    public function updateProductQuantity(int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            throw new CartException('Quantity must be greater than zero.');
        }

        $this->cart->updateItemQuantity($productId, $quantity);

        $this->repository->save($this->cart);
    }

    /**
     * Elimina un producto del carrito.
     */
    public function removeProduct(int $productId): void
    {
        $this->cart->removeItem($productId);

        $this->repository->save($this->cart);
    }

    /**
     * Obtiene todos los productos del carrito.
     *
     * @return array<int, CartItem>
     */
    public function getProducts(): array
    {
        return $this->cart->getItems();
    }

    /**
     * Obtiene el número total de productos en el carrito.
     */
    public function getTotalProducts(): int
    {
        return $this->cart->getTotalQuantity();
    }

    /**
     * Obtiene el precio total del carrito.
     */
    public function getTotalPrice(): float
    {
        return $this->cart->getTotalPrice();
    }

    /**
     * Recarga el carrito desde el repositorio.
     */
    public function loadCart(): void
    {
        $this->cart = $this->repository->load();
    }

    public function confirmPurchase(): void
    {
        if (empty($this->cart->getItems())) {
            throw new \RuntimeException('The cart is empty. Cannot confirm purchase.');
        }

        // Procesa la compra
        foreach ($this->cart->getItems() as $item) {
            $this->repository->saveOrder($item); // Nuevo método en el repositorio
        }

        $this->cart->clear();

        $this->repository->save($this->cart);
    }
}
