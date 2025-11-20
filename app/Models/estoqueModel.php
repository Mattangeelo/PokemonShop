<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class estoqueModel extends Model
{
    use HasFactory;


    protected $table = 'estoques';


    protected $primaryKey = 'id';


    protected $fillable = [
        'id_produto',
        'quantidade',
        'quantidade_minima'
    ];


    protected $casts = [
        'quantidade' => 'integer',
        'quantidade_minima' => 'integer'
    ];
    public function produto()
    {
        return $this->belongsTo(produtoModel::class, 'id_produto');
    }

    public function validaEstoque($qtd, $produtoId)
    {
        $estoque = $this->where('id_produto', $produtoId)->value('quantidade');

        if ($estoque === null) {
            return false;
        }

        return $estoque >= $qtd;
    }
}
