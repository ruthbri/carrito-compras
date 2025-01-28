<?php

namespace App\Domain\Exceptions;

use Exception;

class CartException extends Exception
{
    /**
     * Constructor para crear una excepción específica del carrito.
     *
     * @param string $message El mensaje de error.
     * @param int $code Código de error (opcional).
     * @param Exception|null $previous Excepción anterior para el encadenamiento (opcional).
     */
    public function __construct(string $message, int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Crea una excepción para un producto no encontrado.
     *
     * @param int $productId El ID del producto que no se encontró.
     * @return CartException
     */
    public static function productNotFound(int $productId): self
    {
        return new self("Product with ID {$productId} not found in the cart.", 404);
    }

    /**
     * Crea una excepción para un carrito vacío al confirmar la compra.
     *
     * @return CartException
     */
    public static function emptyCartOnPurchase(): self
    {
        return new self("Cannot confirm purchase. The cart is empty.", 400);
    }

    /**
     * Crea una excepción genérica relacionada con el carrito.
     *
     * @param string $message Mensaje del error.
     * @return CartException
     */
    public static function genericError(string $message): self
    {
        return new self($message, 500);
    }
}
