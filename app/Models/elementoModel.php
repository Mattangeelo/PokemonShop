<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class elementoModel extends Model
{
    protected $table = 'elementos';
    protected $fillable = ['nome'];

    public function buscaElementos(){
        return $this->where('deleted_at',NULL)->select('id','nome')->paginate(10);
    }
    public function buscaTodosElementos(){
        return $this->where('deleted_at', null)
                ->select('id', 'nome')
                ->get();
    }
    public function buscaElemento($id){
        return $this->where('id',$id)
                    ->where('deleted_at',NULL)
                    ->first();
    }
}
