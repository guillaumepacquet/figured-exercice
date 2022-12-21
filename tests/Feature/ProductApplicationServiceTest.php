<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Stock;
use App\Services\ProductApplicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApplicationServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests for @see ProductApplicationService::useProduct()
     */

    public function test_use_product_reduce_quantity_on_stock()
    {
        $productApplicationService  = new ProductApplicationService();

        $product = Product::factory()->hasStocks(1, [
            'quantity' => 10
        ])->create();

        $productApplicationService->useProduct($product, 3);

        $this->assertEquals(7, $product->stocks[0]->quantity);
    }

    public function test_use_product_reduce_quantity_on_multiple_stock()
    {
        $productApplicationService  = new ProductApplicationService();

        $product = Product::factory()->hasStocks(3, [
            'quantity' => 10
        ])->create();

        $productApplicationService->useProduct($product, 13);

        $this->assertEquals(0, $product->stocks[0]->quantity);
        $this->assertEquals(7, $product->stocks[1]->quantity);
        $this->assertEquals(10, $product->stocks[2]->quantity);
    }

    public function test_use_product_reduce_quantity_are_saved()
    {
        $productApplicationService  = new ProductApplicationService();

        $product = Product::factory()->hasStocks(2, [
            'quantity' => 10
        ])->create();

        $productApplicationService->useProduct($product, 13);

        $stocks = Stock::all();
        $this->assertEquals(0, $stocks[0]->quantity);
        $this->assertEquals(7, $stocks[1]->quantity);
    }

    /**
     * Tests for @see ProductApplicationService::getTotalPriceForQuantity()
     */

    public function test_price_is_right_for_quantity()
    {
        $productApplicationService  = new ProductApplicationService();

        $product = Product::factory()->hasStocks(1, [
            'quantity' => 20,
            'price' => 2.5
        ])->create();

        $price = $productApplicationService->getTotalPriceForQuantity($product, 10);

        $this->assertEquals(25, $price);
    }

    public function test_price_is_retuarn_as_a_float()
    {
        $productApplicationService  = new ProductApplicationService();

        $product = Product::factory()->hasStocks(1, [
            'quantity' => 20,
            'price' => 2.5
        ])->create();

        $price = $productApplicationService->getTotalPriceForQuantity($product, 11);

        $this->assertEquals(27.5, $price);
    }

    public function test_price_is_right_for_quantity_on_multiple_stock()
    {
        $productApplicationService  = new ProductApplicationService();

        $product = Product::factory()->hasStocks(1, [
            'quantity' => 10,
            'price' => 2.5
        ])->hasStocks(1, [
            'quantity' => 20,
            'price' => 5
        ])->create();

        $price = $productApplicationService->getTotalPriceForQuantity($product, 15);

        $this->assertEquals(50, $price);
    }

    public function test_return_minus_one_when_not_enough_quantity()
    {
        $productApplicationService  = new ProductApplicationService();

        $product = Product::factory()->hasStocks(1, [
            'quantity' => 10,
            'price' => 2.5
        ])->hasStocks(1, [
            'quantity' => 5,
            'price' => 5
        ])->create();

        $price = $productApplicationService->getTotalPriceForQuantity($product, 20);

        $this->assertEquals(-1, $price);
    }

    /**
     * Tests for @see ProductApplicationService::isProductHaveEnoughQuantity()
     */

    public function test_have_enough_quantity()
    {
        $productApplicationService  = new ProductApplicationService();

        $product = Product::factory()->hasStocks(1, [
            'quantity' => 10
        ])->create();

        $result = $productApplicationService->isProductHaveEnoughQuantity($product, 5);

        $this->assertTrue($result);
    }

    public function test_have_enough_quantity_on_multiple_stock()
    {
        $productApplicationService  = new ProductApplicationService();

        $product = Product::factory()
            ->hasStocks(1, [
            'quantity' => 0
            ])
            ->hasStocks(1, [
                'quantity' => 20
            ])
            ->hasStocks(1, [
                'quantity' => 15
            ])
            ->create();

        $result = $productApplicationService->isProductHaveEnoughQuantity($product, 25);

        $this->assertTrue($result);
    }

    public function test_does_not_have_enough_quantity()
    {
        $productApplicationService  = new ProductApplicationService();

        $product = Product::factory()
            ->hasStocks(1, [
                'quantity' => 0
            ])
            ->hasStocks(1, [
            'quantity' => 10
            ])
            ->create();

        $result = $productApplicationService->isProductHaveEnoughQuantity($product, 15);

        $this->assertFalse($result);
    }
}
