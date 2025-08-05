<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class categoriaModel extends Model
{
    protected $table = 'categorias';
    protected $fillable = ['nome'];

    public function buscaCategorias(){
        return $this->where('deleted_at',NULL)->select('id','nome')->paginate(10);
    }
    public function buscaCategoria($id){
        return $this->where('id',$id)
                    ->where('deleted_at',NULL)
                    ->first();
    }
}
