<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_name',
        'product_name',
        'qty',
        'transacted_qty',
        'selling_price',
        'total_price',
        'profit',
        'created_at', 
        'updated_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_name', 'name');
    }
}
