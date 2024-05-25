<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
        public function up() {
        Schema::create("menu",function(Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description");
            $table->decimal('price',8,2);
            $table->string('image_url');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
        }

       /* public function down()
        {
            Schema::dropIfExists('menu');
        }*/
    
}

?>