<?php
namespace Tests\Application;

use App\Application\Services\CartService;
use App\Application\DTO\CartItemDTO;
use App\Domain\Cart\Cart;
use App\Domain\Cart\CartItem;
use App\Domain\Cart\CartRepository;
use PHPUnit\Framework\TestCase;

class CartServiceTest extends TestCase
{
    private CartService $cartService;

    /** @var CartRepository&\PHPUnit\Framework\MockObject\MockObject */
    private CartRepository $repositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        // mock del repositorio como implementación de CartRepository
        $this->repositoryMock = $this->createMock(CartRepository::class);

        // Simula un carrito vacío cargado desde el repositorio
        $cart = new Cart();
        $this->repositoryMock->method('load')->willReturn($cart);

        // servicio con el repositorio mockeado
        $this->cartService = new CartService($this->repositoryMock);
    }

    public function testAddProductToCart(): void
    {
        $cartItemDTO = CartItemDTO::fromArray([
            'product_id' => 1,
            'quantity' => 2,
            'price' => 19.99,
        ]);

        $this->repositoryMock->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Cart::class));

        $this->cartService->addProduct($cartItemDTO);

        $products = $this->cartService->getProducts();

        $this->assertCount(1, $products);
        $this->assertEquals(1, $products[1]->getProductId());
        $this->assertEquals(2, $products[1]->getQuantity());
        $this->assertEquals(19.99, $products[1]->getPrice());
    }

    public function testConfirmPurchase(): void
    {
        $cartItemDTO1 = CartItemDTO::fromArray([
            'product_id' => 1,
            'quantity' => 2,
            'price' => 19.99,
        ]);
        $cartItemDTO2 = CartItemDTO::fromArray([
            'product_id' => 2,
            'quantity' => 3,
            'price' => 9.99,
        ]);

        $this->cartService->addProduct($cartItemDTO1);
        $this->cartService->addProduct($cartItemDTO2);

        $this->repositoryMock->expects($this->exactly(2))
            ->method('saveOrder')
            ->withConsecutive(
                [$this->isInstanceOf(CartItem::class)],
                [$this->isInstanceOf(CartItem::class)]
            );

        $this->repositoryMock->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Cart::class));

        // Confirma la compra
        $this->cartService->confirmPurchase();

        // Verifica que el carrito esté vacío después de la compra
        $this->assertEmpty($this->cartService->getProducts());
    }
}
