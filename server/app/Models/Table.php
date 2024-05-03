<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Table extends Model {
    use hasFactory;

    protected $fillable = [
        'table_name',
        'capacity'
    ];

    protected $table = 'table';
}

?>