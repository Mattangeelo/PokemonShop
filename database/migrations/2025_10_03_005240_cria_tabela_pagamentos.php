<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuarios');
            $table->foreignId('id_pedido')->nullable()->constrained('pedidos');
            
            // Dados do Mercado Pago
            $table->bigInteger('payment_id')->unique()->nullable();
            $table->string('external_reference', 255)->nullable();
            
            // Informações da transação
            $table->decimal('transaction_amount', 10, 2);
            $table->string('currency_id', 3)->default('BRL');
            $table->text('description')->nullable();
            
            // Status do pagamento
            $table->string('status', 50);
            $table->string('status_detail', 100)->nullable();
            
            // Método de pagamento
            $table->string('payment_method_id', 50)->nullable();
            $table->string('payment_type_id', 50)->nullable();
            
            // Datas importantes
            $table->timestamp('date_created')->nullable();
            $table->timestamp('date_approved')->nullable();
            $table->timestamp('date_last_updated')->nullable();
            $table->timestamp('expiration_date')->nullable();
            
            // Informações do comprador
            $table->string('payer_email', 255)->nullable();
            $table->string('payer_identification_type', 50)->nullable();
            $table->string('payer_identification_number', 50)->nullable();
            
            // URLs
            $table->string('notification_url', 500)->nullable();
            $table->string('callback_url', 500)->nullable();
            
            // Dados adicionais
            $table->json('metadata')->nullable();
            $table->json('raw_response')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagamentos');
    }
};