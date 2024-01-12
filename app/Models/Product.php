<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
        'brand_name',
        'description',
        'category',
        'photo',
        'quantity',
        'low_quantity_threshold',
        'purchase_price',
        'selling_price',
    ];

    // Define the relationship to transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'product_name', 'name');
    }
}
