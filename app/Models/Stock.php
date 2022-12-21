<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @param int $quantity
     * @return int
     */
    public function getMaxQuantityToBeUsed(int $quantity): int
    {
        if ($quantity >= $this->quantity) {
            return $this->quantity;
        }

        return $quantity;
    }

    /**
     * @param int $quantity
     * @return void
     */
    public function useQuantity(int $quantity)
    {
        $this->quantity -= $quantity;
    }
}
