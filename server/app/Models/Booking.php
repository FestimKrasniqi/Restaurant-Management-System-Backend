<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Booking extends Model {
    use hasFactory;

    protected $fillable = [
        'number_of_guests',
        'dateTime'
    ];

    protected $table = 'booking';

    public function user() {
        return $this->belongsTo(User::class);
    }
}



?>