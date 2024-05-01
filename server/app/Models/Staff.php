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

    protected $hidden = [
        'salary'
    ];

    public function shifts() {
        return $this->hasMany(Shift::class);
    }
}



?>