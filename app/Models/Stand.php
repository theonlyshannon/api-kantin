<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stand extends Model
{
    protected $table = 'stands';

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
