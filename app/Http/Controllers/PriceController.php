<?php

namespace App\Http\Controllers;

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
        return Inertia::render('Price', [
            'price' => 34,
        ]);
    }
}
