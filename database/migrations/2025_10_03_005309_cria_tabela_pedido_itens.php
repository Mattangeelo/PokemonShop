<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pedido_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pedido')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('id_produto')->constrained('produtos');
            $table->integer('quantidade');
            $table->decimal('valor_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2)->virtualAs('quantidade * valor_unitario');
            
            // Snapshot do produto
            $table->string('produto_nome', 255)->nullable();
            $table->string('produto_imagem', 500)->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedido_itens');
    }
};