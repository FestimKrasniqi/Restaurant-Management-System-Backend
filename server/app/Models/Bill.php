<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Bill extends Model {

    protected $fillable = [
        'total_amount'

    ];

    protected $table = 'Bill';


    public function order()
    {
        return $this->hasOne(Order::class);
    }
}


?>