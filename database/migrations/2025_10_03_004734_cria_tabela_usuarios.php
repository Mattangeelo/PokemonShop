<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 255);
            $table->string('email', 255)->unique();
            $table->string('telefone', 20)->unique()->nullable();
            $table->string('cpf', 14)->unique();
            $table->foreignId('id_categoria')->constrained('categoria_usuarios');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};