<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Food extends Model
{
    protected $table = 'foods';
    use SoftDeletes;

    protected $fillable = [
        'image',
        'name',
        'slug',
        'description',
        'price',
        'is_discount',
        'discount',
        'discount_price',
    ];

    protected $casts = [
        'is_discount' => 'boolean',
        'discount' => 'decimal:4',
    ];
}
