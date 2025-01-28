<?php

namespace App\Domain\Cart;

class Cart
{
    /** @var array<int, CartItem> */
    private array $items = [];

    /**
     * Agrega un producto al carrito.
     */
    public function addItem(CartItem $item): void
    {
        $productId = $item->getProductId();

        if ($this->hasItem($productId)) {

            $existingItem = $this->items[$productId];
            $existingItem->setQuantity($existingItem->getQuantity() + $item->getQuantity());

        } else {

            $this->items[$productId] = $item;
        }
    }

    /**
     * Actualiza la cantidad de un producto en el carrito.
     */
    public function updateItemQuantity(int $productId, int $quantity): void
    {
        if (!$this->hasItem($productId)) {
            throw new \RuntimeException("Product not found in the cart.");
        }

        if ($quantity <= 0) {
            throw new \RuntimeException("Quantity must be greater than zero.");
        }

        $this->items[$productId]->setQuantity($quantity);
    }

    /**
     * Elimina un producto del carrito.
     */
    public function removeItem(int $productId): void
    {
        if (!$this->hasItem($productId)) {
            throw new \RuntimeException("Product not found in the cart.");
        }

        unset($this->items[$productId]);
    }

    /**
     * Vacía el carrito eliminando todos los productos.
     */
    public function clear(): void
    {
        $this->items = [];
    }

    /**
     * Verifica si un producto está en el carrito.
     */
    public function hasItem(int $productId): bool
    {
        return isset($this->items[$productId]);
    }

    /**
     * Devuelve el número total de productos en el carrito.
     */
    public function getTotalItems(): int
    {
        return count($this->items);
    }

    /**
     * Calcula la cantidad total de productos (sumando todas las cantidades).
     */
    public function getTotalQuantity(): int
    {
        return array_sum(array_map(
            fn(CartItem $item) => $item->getQuantity(),
            $this->items
        ));
    }

    /**
     * Calcula el precio total del carrito.
     */
    public function getTotalPrice(): float
    {
        return array_sum(array_map(
            fn(CartItem $item) => $item->getQuantity() * $item->getPrice(),
            $this->items
        ));
    }

    /**
     * Devuelve los elementos del carrito.
     *
     * @return array<int, CartItem>
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
