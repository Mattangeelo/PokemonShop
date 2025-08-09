<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPrecoTypeInProdutosTable extends Migration
{
    public function up()
    {
        Schema::table('produtos', function (Blueprint $table) {
            // Alterar de string para decimal com 10 dígitos no total e 2 casas decimais
            $table->decimal('preco', 10, 2)->change();
        });
    }

    public function down()
    {
        Schema::table('produtos', function (Blueprint $table) {
            // Reverter para string em caso de rollback
            $table->string('preco')->change();
        });
    }
}
