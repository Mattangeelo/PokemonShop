<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuarios');
            
            // Status do pedido
            $table->enum('status_pedido', [
                'aguardando_pagamento',
                'pago',
                'em_processamento', 
                'preparando_envio',
                'enviado',
                'entregue',
                'cancelado',
                'reembolsado'
            ])->default('aguardando_pagamento');
            
            // Informações do pedido
            $table->decimal('valor_total', 10, 2);
            $table->decimal('valor_frete', 10, 2)->default(0.00);
            $table->decimal('valor_desconto', 10, 2)->default(0.00);
            
            // Endereço de entrega
            $table->json('endereco_entrega_json')->nullable();
            
            // Observações
            $table->text('observacoes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
};