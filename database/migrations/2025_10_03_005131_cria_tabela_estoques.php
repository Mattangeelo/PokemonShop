<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('estoques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produto')->constrained('produtos')->onDelete('cascade')->unique();
            $table->integer('quantidade')->default(0);
            $table->integer('quantidade_minima')->default(10);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estoques');
    }
};