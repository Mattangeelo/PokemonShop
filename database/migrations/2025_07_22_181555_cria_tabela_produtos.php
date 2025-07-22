<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
       Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->string('descricao');
            $table->string('numeracao');
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('elemento_id');
            $table->string('preco');
            $table->integer('quantidade');
            $table->string('imagem');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->foreign('elemento_id')->references('id')->on('elementos');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
