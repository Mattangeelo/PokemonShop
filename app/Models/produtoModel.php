<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class produtoModel extends Model
{
    protected $table = 'produtos';
    protected $fillable = ['nome','descricao','numeracao','categoria_id','elemento_id','preco','quantidade','imagem'];

    public function buscaProdutos(){
        return $this->where('deleted_at',NULL)->select('id','nome','descricao','numeracao','categoria_id','elemento_id','preco','quantidade','imagem')->paginate(10);
    }
    public function buscaProduto($id){
        return $this->where('id',$id)
                    ->where('deleted_at',NULL)
                    ->first();
    }

    public function categoria()
    {
        return $this->belongsTo(categoriaModel::class);
    }

    public function elemento()
    {
        return $this->belongsTo(elementoModel::class);
    }
}
