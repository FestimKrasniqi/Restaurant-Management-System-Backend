<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Menu extends Model {
   use hasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image_url'

    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function order() {
        return $this->hasMany(Order::class);
    }

    protected $table = 'menu';

}


?>