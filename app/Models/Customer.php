<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
    // 'name', 
    'customer_name_id', 
    'contact_name', 'address', 'contact_num', 'item_sold'];
}
