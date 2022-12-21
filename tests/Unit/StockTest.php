<?php

namespace Tests\Unit;

use App\Models\Stock;
use PHPUnit\Framework\TestCase;

class StockTest extends TestCase
{
    public function test_when_quantity_given_is_lower_than_stock_quantity()
    {
        $stock = new Stock(['quantity' => 10]);

        $result = $stock->getMaxQuantityToBeUsed(7);

        $this->assertEquals(7, $result);
    }

    public function test_when_quantity_given_is_higher_than_stock_quantity()
    {
        $stock = new Stock(['quantity' => 10]);

        $result = $stock->getMaxQuantityToBeUsed(13);

        $this->assertEquals(10, $result);
    }
}
