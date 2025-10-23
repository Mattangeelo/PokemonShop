<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class categoriaModel extends Model
{
    protected $table = 'categorias';
    protected $fillable = ['nome'];

    public function buscaCategorias(){
        return $this->select('id','nome')->paginate(10);
    }
    public function buscaTodasCategorias(){
        return $this
                ->select('id', 'nome')
                ->get();
    }
    public function buscaCategoria($id){
        return $this->where('id',$id)
                    ->first();
    }
}
