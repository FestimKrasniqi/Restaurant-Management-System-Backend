<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model {
   use hasFactory;

    protected $fillable = [
        'category_name',
       

    ];

    protected $table = 'category';

    
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

   

}


?>