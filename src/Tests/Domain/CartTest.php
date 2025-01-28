<?php

namespace Tests\Domain;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartItem;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    public function testCartStartsEmpty(): void
    {
        $cart = new Cart();
        $this->assertEmpty($cart->getItems());
    }

    public function testAddItemToCart(): void
    {
        $cart = new Cart();
        $item = new CartItem(1, 2, 19.99);

        $cart->addItem($item);

        $this->assertCount(1, $cart->getItems());
        $this->assertSame($item, $cart->getItems()[1]);
    }

    public function testUpdateItemQuantity(): void
    {
        $cart = new Cart();
        $item = new CartItem(1, 2, 19.99);
        $cart->addItem($item);

        $cart->updateItemQuantity(1, 5);

        $this->assertEquals(5, $cart->getItems()[1]->getQuantity());
    }

    public function testRemoveItemFromCart(): void
    {
        $cart = new Cart();
        $item = new CartItem(1, 2, 19.99);
        $cart->addItem($item);

        $cart->removeItem(1);

        $this->assertEmpty($cart->getItems());
    }

    public function testClearCart(): void
    {
        $cart = new Cart();
        $cart->addItem(new CartItem(1, 2, 19.99));
        $cart->addItem(new CartItem(2, 1, 9.99));

        $cart->clear();

        $this->assertEmpty($cart->getItems());
    }

    public function testGetTotalItems(): void
    {
        $cart = new Cart();
        $cart->addItem(new CartItem(1, 2, 19.99));
        $cart->addItem(new CartItem(2, 1, 9.99));

        $this->assertEquals(2, $cart->getTotalItems());
    }

    public function testGetTotalQuantity(): void
    {
        $cart = new Cart();
        $cart->addItem(new CartItem(1, 2, 19.99));
        $cart->addItem(new CartItem(2, 3, 9.99));

        $this->assertEquals(5, $cart->getTotalQuantity());
    }
}
