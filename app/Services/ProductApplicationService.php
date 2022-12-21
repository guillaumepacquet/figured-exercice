<?php

namespace App\Services;

use App\Models\Product;

class ProductApplicationService
{
    /**
     * Use the stocks of a product.
     *
     * @param Product $product
     * @param int $quantity
     * @return void
     */
    public function useProduct(Product $product, int $quantity)
    {
        foreach ($product->stocks as $stock) {
            $maxQuantityToBeUseOnStock = $stock->getMaxQuantityToBeUsed($quantity);
            $stock->useQuantity($maxQuantityToBeUseOnStock);
            $quantity -= $maxQuantityToBeUseOnStock;
        }

        $product->push();
    }

    /**
     * @param Product $product
     * @param int $quantity
     * @return float
     */
    public function getTotalPriceForQuantity(Product $product, int $quantity): float
    {
        if(!$this->isProductHaveEnoughQuantity($product, $quantity)) {
           return -1;
        }

        $price = 0;
        foreach ($product->stocks as $stock) {
            $maxQuantity = $stock->getMaxQuantityToBeUsed($quantity);
            $price += $stock->price * $maxQuantity;
            $quantity -= $maxQuantity;
        }

        return $price;
    }

    /**
     * @param Product $product
     * @param int $quantity
     * @return bool
     */
    public function isProductHaveEnoughQuantity(Product $product, int $quantity): bool
    {
        $stockQuantity = array_reduce($product->stocks->all(), function($quantity, $stock) {
            $quantity += $stock->quantity;
            return $quantity;
        });

        return $stockQuantity >= $quantity;
    }
}
