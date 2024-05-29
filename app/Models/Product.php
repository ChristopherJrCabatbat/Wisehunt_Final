<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'product_name_id',
        // 'name',
        'brand_name',
        'description',
        'unit',
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
        return $this->hasMany(Transaction::class, 'product_name', 'product_name_id');
    }
}
