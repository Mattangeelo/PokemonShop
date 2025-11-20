<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    use HasFactory;

    protected $table = 'pedido_itens';

    protected $fillable = [
        'id_pedido',
        'id_produto',
        'quantidade',
        'valor_unitario',
        'subtotal',
        'produto_nome',
        'produto_imagem',
    ];

    // Relacionamentos
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    public function produto()
    {
        return $this->belongsTo(ProdutoModel::class, 'id_produto');
    }
}
