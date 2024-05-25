<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends migration {
    public function up() {
        Schema::create('table',function(Blueprint $table) {
            $table->id();
            $table->string('table_name');
            $table->integer('capacity');
            $table->timestamps();
        });
    }
}

?>