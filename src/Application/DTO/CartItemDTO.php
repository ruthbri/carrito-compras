<?php

namespace App\Application\DTO;

class CartItemDTO
{
    private int $productId;
    private int $quantity;
    private float $price;

    private function __construct(int $productId, int $quantity, float $price)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->price = $price;
    }


    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): CartItemDTO
    {
        if (!isset($data['product_id'], $data['quantity'], $data['price'])) {
            throw new \InvalidArgumentException('Missing required fields: product_id, quantity, or price');
        }

        return new self((int) $data['product_id'], (int) $data['quantity'], (float) $data['price']);
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
