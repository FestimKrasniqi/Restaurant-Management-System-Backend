<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model {
    use hasFactory;

    protected $fillable = [
        'start_time',
        'end_time',
        
    ];

    public function shifts() {
        return $this->belongsTo(Staff::class);
    }
}



?>