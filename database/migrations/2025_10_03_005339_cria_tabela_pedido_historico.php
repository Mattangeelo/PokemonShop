<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pedido_historico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pedido')->constrained('pedidos')->onDelete('cascade');
            $table->string('status_anterior', 50)->nullable();
            $table->string('status_novo', 50);
            $table->text('observacao')->nullable();
            $table->foreignId('id_usuario_responsavel')->nullable()->constrained('usuarios');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedido_historico');
    }
};