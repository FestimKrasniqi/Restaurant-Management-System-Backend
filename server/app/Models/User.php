<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;




class User extends Authenticatable
{
   
    use HasFactory;
    use Notifiable;
   

   
    protected $fillable = [
        'first',
        'email',
        'password',
        'role',
        'last',
        'confirm_password'
    ];

   
    protected $hidden = [
        'password',
        'confirm_password',
        'remember_token',

       
    ];

  

    
   protected $casts = [
'email_verified_at' => 'datetime',
   ];
}
