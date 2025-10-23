<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 255);
            $table->text('descricao')->nullable();
            $table->string('numeracao', 50)->nullable();
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->foreignId('elemento_id')->nullable()->constrained('elementos');
            $table->decimal('preco', 10, 2);
            $table->string('imagem_principal', 500)->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produtos');
    }
};