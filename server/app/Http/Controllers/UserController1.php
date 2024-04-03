<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController1 {
    function register(Request $req) {
       
        
        $user = new User;
        $user->first = $req->input('first');
        $user->last = $req->input('last');
        $user->email = $req->input('email');
        $user->role = $req->input('role');
        $user->password = Hash::make($req->input('password'));
        $user->save();
        return $user;
    


    }
}
