<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('shifts',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
            $table->foreign('staff_id')->references('id')->on('staff');


        });
    }
}


?>