<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift  extends Model {
    use hasFactory;

    protected $fillable = [
        'start_time',
        'end_time',
        
    ];

    protected $table = 'shifts';

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
}



?>