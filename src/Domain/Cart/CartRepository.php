<?php

namespace App\Domain\Cart;

interface CartRepository
{
    /**
     * Guarda el carrito en el repositorio.
     *
     * @param Cart $cart
     */
    public function save(Cart $cart): void;

    /**
     * Carga el carrito desde el repositorio.
     *
     * @return Cart
     */
    public function load(): Cart;

    /**
     * Elimina el carrito del repositorio.
     */
    public function clear(): void;

    /**
     * Guarda un producto del carrito en la tabla `orders` (Confirmar la compra del carrito).
     *
     * @param CartItem $item
     */
    public function saveOrder(CartItem $item): void;
}
