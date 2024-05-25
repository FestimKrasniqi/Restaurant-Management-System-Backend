<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model {
    use hasFactory;

    protected $fillable = [
        'FullName',
        'role',
        'salary'
    ];

   

    protected $table = 'staff';

    

    public function shift() {
        return $this->belongsTo(Shift::class);
    }
}



?>