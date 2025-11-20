<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    protected $table = 'pagamentos';

    protected $fillable = [
        'id_usuario',
        'id_pedido',
        'transaction_amount',
        'currency_id',
        'description',
        'status',
        'status_detail',
        'payment_method_id',
        'payment_type_id',
        'date_created',
        'date_approved',
        'payer_email',
    ];

    protected $dates = [
        'date_created',
        'date_approved',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
