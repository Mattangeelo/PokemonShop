<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoHistorico extends Model
{
    use HasFactory;

    protected $table = 'pedido_historico';

    protected $fillable = [
        'id_pedido',
        'status_anterior',
        'status_novo',
        'observacao',
        'id_usuario_responsavel',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario_responsavel');
    }
}
