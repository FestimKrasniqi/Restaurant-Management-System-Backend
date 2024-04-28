<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {

        Schema::create('category', function(Blueprint $table){

       $table->id();
       $table->string('category_name');
       $table->unsignedBigInteger('menu_id');
       $table->timestamps();
       $table->foreign('menu_id')->references('id')->on('menu');
        });
    }

    /*public function down() {
        Schema::dropIfExists('category');
    }*/
}

?>