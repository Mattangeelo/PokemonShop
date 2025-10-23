<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('moedas', function (Blueprint $table) {
            $table->id();
            $table->integer('quantidade_moedas')->default(0);
            $table->foreignId('id_usuario')->constrained('usuarios')->onDelete('cascade')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('moedas');
    }
};