<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'id_usuario',
        'status_pedido',
        'valor_total',
        'valor_frete',
        'valor_desconto',
        'endereco_entrega_json',
        'observacoes',
    ];

    protected $casts = [
        'endereco_entrega_json' => 'array',
    ];

    // Relacionamentos
    public function itens()
    {
        return $this->hasMany(PedidoItem::class, 'id_pedido');
    }

    public function historico()
    {
        return $this->hasMany(PedidoHistorico::class, 'id_pedido');
    }

    public function pagamento()
    {
        return $this->hasOne(Pagamento::class, 'id_pedido');
    }

    public function usuario()
    {
        return $this->belongsTo(usuarioModel::class, 'id_usuario');
    }
}
