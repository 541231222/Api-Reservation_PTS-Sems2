<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'image',
        'brand_name',
        'price_per_day',
        'stock'
    ];

    protected $casts = [
        'price_per_day' => 'int',
        'stock' => 'int'
    ];

    public function reservation()
    {
        return $this->hasMany(Reservation::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //testing account
}


