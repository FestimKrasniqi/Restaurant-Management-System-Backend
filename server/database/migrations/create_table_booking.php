<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends migration {
    public function up() {
        Schema::create('booking',function(Blueprint $table){
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->dateTime('datetime');
        $table->integer('number_of_guests');
        $table->timestamps();
        $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}



?>