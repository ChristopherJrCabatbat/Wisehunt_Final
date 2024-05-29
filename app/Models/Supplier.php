<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = ['company_name', 'contact_name', 'address', 
    // 'product_name',
    'product_name_id', 
    'contact_num', 'quantity'];

    protected $casts = [
        'product_name_id' => 'array',
    ];
}
