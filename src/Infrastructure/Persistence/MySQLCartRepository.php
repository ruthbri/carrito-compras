<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartItem;
use App\Domain\Cart\CartRepository;
use PDO;
use PDOException;

class MySQLCartRepository implements CartRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Guarda el carrito en la base de datos.
     *
     * @param Cart $cart
     */
    public function save(Cart $cart): void
    {
        try {
            $this->pdo->beginTransaction();

            $this->clear();

            $stmt = $this->pdo->prepare(
                'INSERT INTO cart_items (product_id, quantity, price) VALUES (:product_id, :quantity, :price)'
            );

            foreach ($cart->getItems() as $item) {
                $stmt->execute([
                    ':product_id' => $item->getProductId(),
                    ':quantity'   => $item->getQuantity(),
                    ':price'      => $item->getPrice(),
                ]);
            }

            $this->pdo->commit();

        } catch (PDOException $e) {

            $this->pdo->rollBack();

            throw new \RuntimeException('Failed to save cart: ' . $e->getMessage());
        }
    }

    /**
     * Carga el carrito desde la base de datos.
     *
     * @return Cart
     */
    public function load(): Cart
    {
        $cart = new Cart();

        try {
            $stmt = $this->pdo->query('SELECT product_id, quantity, price FROM cart_items');

            if ($stmt === false) {
                throw new \RuntimeException('Failed to execute query.');
            }

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $cart->addItem(new CartItem(
                    (int) $row['product_id'],
                    (int) $row['quantity'],
                    (float) $row['price']
                ));
            }
        } catch (PDOException $e) {

            throw new \RuntimeException('Failed to load cart: ' . $e->getMessage());
        }

        return $cart;
    }

    /**
     * Limpia todos los elementos del carrito en la base de datos.
     */
    public function clear(): void
    {
        try {

            $this->pdo->exec('DELETE FROM cart_items');

        } catch (PDOException $e) {

            throw new \RuntimeException('Failed to clear cart items: ' . $e->getMessage());
        }
    }

    /**
     * Genera la orden.
     */
    public function saveOrder(CartItem $item): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO orders (product_id, quantity, price, total_price, order_date)
            VALUES (:product_id, :quantity, :price, :total_price, NOW())'
        );

        $stmt->execute([
            ':product_id'   => $item->getProductId(),
            ':quantity'     => $item->getQuantity(),
            ':price'        => $item->getPrice(),
            ':total_price'  => $item->calculateTotalPrice(),
        ]);
    }
}
