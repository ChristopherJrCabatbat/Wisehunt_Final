<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $fillable = ['delivery_id', 
    // 'name', 
    'customer_name_id', 
    'product', 'quantity', 'mode_of_payment', 'status'];   
}
