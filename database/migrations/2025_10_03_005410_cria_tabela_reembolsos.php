<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reembolsos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pagamento')->constrained('pagamentos');
            $table->foreignId('id_pedido')->constrained('pedidos');
            $table->decimal('valor_reembolsado', 10, 2);
            $table->text('motivo')->nullable();
            $table->enum('status_reembolso', ['solicitado', 'processando', 'concluido', 'falha'])->default('solicitado');
            $table->string('id_refund_mp', 100)->nullable();
            $table->timestamp('data_solicitacao')->useCurrent();
            $table->timestamp('data_conclusao')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reembolsos');
    }
};