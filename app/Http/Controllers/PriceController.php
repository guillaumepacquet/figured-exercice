<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductApplicationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PriceController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function getPrice(Request $request)
    {
        $quantity = $request->get('quantity');

        if (null === $quantity) {
            return Inertia::render('Price');
        }

        $product = Product::all()->find(1);
        $productApplicationService = new ProductApplicationService();

        $price = $productApplicationService->getTotalPriceForQuantity($product, $quantity);

        return Inertia::render('Price', [
            'price' => $price,
        ]);
    }
}
