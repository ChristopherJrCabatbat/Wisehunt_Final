<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returned extends Model
{
    use HasFactory;
    
    protected $fillable = [
    'company_name', 
    'contact_name', 
    'contact_number', 
    'returned_product', 
    'reason', 
    'date_returned',  
   ];

    protected $casts = [
        'returned_product' => 'array',
    ];
}