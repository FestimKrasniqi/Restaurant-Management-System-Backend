<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    use hasFactory;

    protected $fillable = [
        'quantity'
    ];

    protected $table = 'order';


    function user(){
        return $this->belongsTo(User::class);
    }

    function menu() {
        return $this->belongsTo(Menu::class);
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}

?>