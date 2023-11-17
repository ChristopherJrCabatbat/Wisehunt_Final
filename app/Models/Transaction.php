<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_name',
        'contact_num',
        'product_name',
        'qty',
        'unit_price',
        'total_price',
        'amount_tendered',
        'change_due',
        'total_earned',

        // Hindi pa na-migrate
        'created_at', 
        'updated_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_name', 'name');
    }
}
