<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('produto_imagens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produto')->constrained('produtos')->onDelete('cascade');
            $table->string('caminho_imagem', 500);
            $table->string('nome_arquivo', 255);
            $table->integer('ordem')->default(0);
            $table->boolean('principal')->default(false);
            $table->text('legenda')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produto_imagens');
    }
};