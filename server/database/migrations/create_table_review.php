<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {

    public function up() {
        Schema::create('review',function(Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('user_id');
          $table->integer('rating');
          $table->text('comment');
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
          $table->timestamps();
          


        });
    }
}


?>