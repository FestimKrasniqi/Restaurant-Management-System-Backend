<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create("staff",function(Blueprint $table) {
            $table->id();
            $table->decimal('salary',8,2);
            $table->string('FullName');
            $table->enum('role',['chef','waiter','manager','cleaner','cuisiner'])->default('waiter');
            $table->unsignedBigInteger('shift_id');
            $table->foreign('shift_id')->references('id')->on('shifts');
            $table->timestamps();
        });
    }
}

?>