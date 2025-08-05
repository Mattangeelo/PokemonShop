<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cpf')->unique();
            $table->string('email')->unique();
            $table->string('senha');
            $table->string('telefone')->unique();
            $table->char('cep',9);
            $table->string('logradouro',100);
            $table->string('numero',10)->nullable();
            $table->string('complemento',100)->nullable();
            $table->string('bairro',60);
            $table->string('cidade',60);
            $table->char('uf',2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
