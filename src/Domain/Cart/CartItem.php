<?php

namespace App\Domain\Cart;

class CartItem
{
    private int $productId;
    private int $quantity;
    private float $price;

    /**
     *
     * @param int $productId El ID del producto.
     * @param int $quantity La cantidad del producto.
     * @param float $price El precio unitario del producto.
     * @throws \InvalidArgumentException Si la cantidad o el precio son inválidos.
     */
    public function __construct(int $productId, int $quantity, float $price)
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Quantity must be greater than zero.');
        }

        if ($price < 0) {
            throw new \InvalidArgumentException('Price cannot be negative.');
        }

        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    /**
     * Obtiene el ID del producto.
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * Obtiene la cantidad del producto.
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Obtiene el precio unitario del producto.
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Establece una nueva cantidad para el producto.
     *
     * @param int $quantity La nueva cantidad.
     * @throws \InvalidArgumentException Si la cantidad es menor o igual a cero.
     */
    public function setQuantity(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Quantity must be greater than zero.');
        }

        $this->quantity = $quantity;
    }

    /**
     * Calcula el precio total del producto basado en la cantidad.
     */
    public function calculateTotalPrice(): float
    {
        return $this->quantity * $this->price;
    }
}
