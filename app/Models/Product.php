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
        'description',
        'category',
        'photo',
        'quantity',
        'capital',
        'unit_price',
    ];

    // Define the relationship to transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'product_name', 'name');
    }
}
